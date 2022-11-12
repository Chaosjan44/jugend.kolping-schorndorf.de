function unMarkPrev(textarea) {
    output = unMark(textarea.value);
    document.getElementById("prevModalText").innerHTML = output;
    const myModal = new bootstrap.Modal('#prevModal');
    const modalToggle = document.getElementById('prevModal');
    myModal.show(modalToggle);
}

function unMark(input) {
    // Replaces all line breaks with a string i will use later
    input = input.replaceAll(/(?:\r\n|\r|\n)/g, '<br><split>');

    // Replaces the ** with bold text
    input = input.replaceAll('**', '<bsplit><b>');
    var inputArray = input.split('<bsplit>');
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        if (i % 2 == 0 && i != 0) {
            inputArray[i] = inputArray[i].replace('<b>', '</b>');
        }
        input += inputArray[i]
    }

    // Replaces the ___ with italic text
    input = input.replaceAll('___', '<isplit><i>');
    var inputArray = input.split('<isplit>');
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        if (i % 2 == 0 && i != 0) {
            inputArray[i] = inputArray[i].replace('<i>', '</i>');
        }
        input += inputArray[i]
    }

    // Replaces the ~~ with strikethrough text
    input = input.replaceAll('~~', '<delsplit><del>');
    var inputArray = input.split('<delsplit>');
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        if (i % 2 == 0 && i != 0) {
            inputArray[i] = inputArray[i].replace('<del>', '</del>');
        }
        input += inputArray[i]
    }

    // replaces the __ with underlined text
    input = input.replaceAll('__', '<inssplit><ins>');
    var inputArray = input.split('<inssplit>');
    input = "";
    for (i = 0; i < inputArray.length; i++) {
        if (i % 2 == 0 && i != 0) {
            inputArray[i] = inputArray[i].replace('<ins>', '</ins>');
        }
        input += inputArray[i]
    }

    // Splits the text into Lines
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
            string = before + '<a class="link" target="_blank" href="' + link + '">' + title + "</a>" + after
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