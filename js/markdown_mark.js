// Function to get the selected text
function getSel(textarea) {
    var start = textarea.selectionStart;
    var finish = textarea.selectionEnd;
    return textarea.value.substring(start, finish);
}

// Function to get the start and end of the selected text
function getSelNum(textarea) {
    var start = textarea.selectionStart;
    var finish = textarea.selectionEnd;
    return {
        start: start,
        end: finish
    };
}

// Function to replace selected text
function replaceSelectedText(textarea, text) {
    var sel = getSelNum(textarea), val = textarea.value;
    textarea.value = val.slice(0, sel.start) + text + val.slice(sel.end);
}

// 
// Functions to markdown
// 

// Function to make text bold
function makeBold(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "**" + input + "/**";
    replaceSelectedText(textarea, replace);
}

// Function to make text Italic
function makeItalic(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "*_" + input + "/*_";
    replaceSelectedText(textarea, replace);
}

// Function to make text strikethrough
function makeStrikethrough(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "~~" + input + "/~~";
    replaceSelectedText(textarea, replace);
}

// Function to make text underline 
function makeUnderline(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "__" + input + "/__";
    replaceSelectedText(textarea, replace);
}

// Function to make text a heading
function makeHeading(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "##" + input;
    replaceSelectedText(textarea, replace);
}

// Function to make text a link
function makeLink(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "[" + input + "](https://www.example.com)";
    replaceSelectedText(textarea, replace);
}

// Function to make text into a list
function makeList(textarea) {
    textarea.focus();
    input = getSel(textarea);
    replace = "- " + input;
    replaceSelectedText(textarea, replace);
}
