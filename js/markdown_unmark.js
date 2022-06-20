function unMarkPrev(textarea) {
    input = textarea.value;
    output = unMark(input);
    input = "";
    document.getElementById("prevModalText").innerHTML = output;
    const myModal = new bootstrap.Modal('#prevModal');
    const modalToggle = document.getElementById('prevModal');
    myModal.show(modalToggle);
}
function unMark(input) {
    input = input.replace(/(?:\r\n|\r|\n)/g, '<br><split>');
    input = input.replace('**', '<b>');
    input = input.replace('/**', '</b>');
    input = input.replace('*_', '<i>');
    input = input.replace('/*_', '</i>');
    input = input.replace('~~', '<del>');
    input = input.replace('/~~', '</del>');
    input = input.replace('__', '<ins>');
    input = input.replace('/__', '</ins>');
    inputArray = input.split('<split>');
    
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        // Heading
        if (inputArray[i].startsWith('##')) {
            inputArray[i] = inputArray[i].replace('##', '<h2>');
            inputArray[i] = inputArray[i].replace('<br>', '</h2><br>');
        }
        // Link
        if (inputArray[i].includes('(http')) {
            string = inputArray[i]
            title = string.substring(
                string.lastIndexOf("[") + 1, 
                string.lastIndexOf("]")
            );
            link = string.substring(
                string.lastIndexOf("(") + 1, 
                string.lastIndexOf(")")
            );
            string = '<a class="hoverlink" href="' + link + '">' + title + "</a>"
            inputArray[i] = string;
        }
        // List
        if (inputArray[i].startsWith('- ')) {
            inputArray[i] = inputArray[i].replace('- ', '<li>');
            inputArray[i] = inputArray[i].replace('<br>', '</li>');
        }
        input += inputArray[i]
    }
   
    return input;
}


function replaceSelectedText(textarea, text) {
    var sel = getSelNum(textarea), val = textarea.value;
    textarea.value = val.slice(0, sel.start) + text + val.slice(sel.end);
}

