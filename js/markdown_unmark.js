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
        // Link
        if (inputArray[i].includes('(http')) {
            string = inputArray[i]
            before = string.substring(
                0,
                string.indexOf('[')
            );
            title = string.substring(
                string.lastIndexOf('[') + 1, 
                string.lastIndexOf(']')
            );
            link = string.substring(
                string.lastIndexOf('(') + 1, 
                string.lastIndexOf(')')
            );
            after = string.substring(
                string.lastIndexOf(')') +1
            );
            string = before + '<a class="link" href="' + link + '">' + title + "</a>" + after
            inputArray[i] = string;
        }
        if (inputArray[i].startsWith('##')) {
            inputArray[i] = inputArray[i].replace('##', '<h2>');
            inputArray[i] = inputArray[i].replace('<br>', '</h2><br>');
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

