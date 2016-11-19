calcIndex = 0;
function Parser() {
    this.MODES = {NONE:0,LIST:1}
    this.calcs = [];
    this.mode = this.MODES.NONE;
    this.fnTitle =  function(data){
        return "<h1>" + data + "</h1>"; 
    };
    this.fnGraph = function(data){
        var ret = "";
        var uid = calcIndex;
        calcIndex++;
        ret = ret.concat("<div id=\"", uid, "\" style=\"width: 100%; height:400px;\"></div>");
        this.calcs.push({uid, data});
        return ret;
    };
    this.fnBold = function(data){
        return "<b>" + data + "</b>";
    }
    this.fnBegin = function(data){
        if (data == "list"){
            this.mode = this.MODES.LIST;
            return "<ul>";
        }
    }
    this.fnEnd = function(data){
        if (data == "list"){
            this.mode = this.MODES.NONE;
            return "</ul>";
        }
    }
    this.fnItem = function(data){
        return "<li>" + data + "</li>";
    }
    this.execFns = function (line, par = false, ignoreMode = false){
        var pre = "", post = "", ret = "";
        var fnLocs = [-1, -1, -1, -1];
        var bCount = 0, cCount = 0;
        for (i = 0; i < line.length; ++i){
            if (line[i] == '('){
                bCount++;
            }else if (line[i] == ')'){
                bCount--;
            }else if (line[i] == '{'){
                cCount++;
            }else if (line[i] == '}'){
                cCount--;
            }
            if (fnLocs[0] == -1 && line[i] == '('){
                fnLocs[0] = i;
            }else if (fnLocs[0] != -1 && fnLocs[1] == -1 && line[i] == ')' && bCount == 0){
                fnLocs[1] = i;
            }else if (fnLocs[1] != -1 && fnLocs[2] == -1 && line[i] == '{'){
                fnLocs[2] = i;
            }else if (fnLocs[2] != -1 && fnLocs[3] == -1 && line[i] == '}' && cCount == 0){
                fnLocs[3] = i;
            }else if (fnLocs[0] == -1){
                pre = pre.concat(line[i]);
            }else if (fnLocs[3] !== -1){
                post = post.concat(line[i]);
            }
        }
        
        if (fnLocs[0] != -1 && fnLocs[1] != -1 && fnLocs[2] != -1 && fnLocs[3] != -1){
            if (par){
                ret = ret.concat("<p>", pre, "</p>");
            }else{
                ret = pre;
            }
            console.log(fnLocs);
            var fnName = line.substring(fnLocs[0] + 1, fnLocs[1]);
            var fnArgs = line.substring(fnLocs[2] + 1, fnLocs[3]);
            if (fnName == "title"){
                ret = ret.concat(this.fnTitle(this.execFns(fnArgs, false, true))); 
            }else if (fnName == "graph"){
                ret = ret.concat(this.fnGraph(fnArgs));
            }else if (fnName == "b"){
                ret = ret.concat(this.fnBold(this.execFns(fnArgs, false, true)));
            }else if (fnName == "begin"){
                ret = ret.concat(this.fnBegin(fnArgs));
            }else if (fnName == "end"){
                ret = ret.concat(this.fnEnd(fnArgs));
            }else if (fnName == "item" || fnName == "i" ){
                ret = ret.concat(this.fnItem(this.execFns(fnArgs, false, true)));
            }
            if (post !== ""){
                ret = ret.concat(this.execFns(post, false, true));
            }
        }else if (!ignoreMode && this.mode != this.MODES.NONE){
            if (this.mode == this.MODES.LIST){
                ret = this.fnItem(this.execFns(line, false, true));
            }
        }else{
            ret = "";
            if (par){
                return ret.concat("<p>", line ,"</p>");
            }else{
                return line;
            }
        }
        
        return ret;

    };
    this.parse = function(data){
        data = data.split('\n');
        var ret = "";
        var mathEnv = false;
        for (var i = 0; i < data.length; i++){
            var line = data[i];
            for (var j = 0; j < line.length; j++){
                if (line[j] == '$' && line[j+1] == '$'){
                    mathEnv = !mathEnv;
                    if (mathEnv == false){
                        ret = ret.concat(line.substring(0, j+2));
                        line = line.substring(j+2, line.length);
                    }
                }
            }
            if (mathEnv == true){
                ret = ret.concat(line); 
            }else{
                ret = ret.concat(this.execFns(line, true));
            }
        }
        return ret; 
    }

}
function cleanScripts(str){
    var asHTML = $(str.bold());
    asHTML.find('script').remove();
    return asHTML.html();
}

function compile(){
    var parser = new Parser();
    var newText = parser.parse($("#raw_in").val());
    
    newText = cleanScripts(newText);
    $("#out").html(newText);
    for (var i = 0; i < parser.calcs.length; ++i){
        var calc = Desmos.Calculator(document.getElementById(parser.calcs[i].uid), {expressions:false,settingsMenu:false,zoomButtons:false});
        calc.setExpression({id:parser.calcs[i].uid, latex:parser.calcs[i].data});
    }
    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
    delete parser;
}
function textChanged(){
    $("#fab-save").addClass("fab-warning");
}

$(document).ready(function(){  
  jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      var sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  });
}
})});
