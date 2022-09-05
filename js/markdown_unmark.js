function unMarkPrev(textarea) {
    output = unMark2(textarea.value);
    document.getElementById("prevModalText").innerHTML = output;
    const myModal = new bootstrap.Modal('#prevModal');
    const modalToggle = document.getElementById('prevModal');
    myModal.show(modalToggle);
}

function unMark2(input) {
    input = input.replace(/(?:\r\n|\r|\n)/g, '<br><split>');
    input = input.replaceAll('**', '<bsplit><b>');
    var inputArray = input.split('<bsplit>');
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        console.log(i);
        if (i + 1 % 2 == 0 && i != 0) {
            inputArray[i].replace('<b>', '</b>');
        }
        input += inputArray[i]
    }
    input.replace()





    var inputArray = input.split('<split>');
    
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        // Link
        if (inputArray[i].includes('(http')) {
            var string = inputArray[i]
            var before = string.substring(
                0,
                string.indexOf('[')
            );
            var title = string.substring(
                string.lastIndexOf('[') + 1, 
                string.lastIndexOf(']')
            );
            var link = string.substring(
                string.lastIndexOf('(') + 1, 
                string.lastIndexOf(')')
            );
            var after = string.substring(
                string.lastIndexOf(')') +1
            );
            string = before + '<a class="link" href="' + link + '">' + title + "</a>" + after
            inputArray[i] = string;
        }
        // Heading
        if (inputArray[i].startsWith('##')) {
            inputArray[i] = inputArray[i].replace('##', '<h2>');
            inputArray[i] = inputArray[i].replace('<br>', '</h2>');
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
    var inputArray = input.split('<split>');
    
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        // Link
        if (inputArray[i].includes('(http')) {
            var string = inputArray[i]
            var before = string.substring(
                0,
                string.indexOf('[')
            );
            var title = string.substring(
                string.lastIndexOf('[') + 1, 
                string.lastIndexOf(']')
            );
            var link = string.substring(
                string.lastIndexOf('(') + 1, 
                string.lastIndexOf(')')
            );
            var after = string.substring(
                string.lastIndexOf(')') +1
            );
            string = before + '<a class="link" href="' + link + '">' + title + "</a>" + after
            inputArray[i] = string;
        }
        // Heading
        if (inputArray[i].startsWith('##')) {
            inputArray[i] = inputArray[i].replace('##', '<h2>');
            inputArray[i] = inputArray[i].replace('<br>', '</h2>');
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

function unMarkToSpan(span) {
    document.getElementById(span).innerHTML = unMark(document.getElementById(span).innerHTML);
}