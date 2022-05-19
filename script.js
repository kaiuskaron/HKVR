let photos = [];

window.onload = () => {
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {
        const filesInput = document.getElementById("files");
        const output = document.getElementById("output");
        const button = document.getElementById('image-upload');
        filesInput.addEventListener("change", (event) => {
            const files = event.target.files; //FileList object
            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                //Only pics
                if (!file.type.match('image'))
                    continue;
                const picReader = new FileReader();
                picReader.addEventListener("load", (event) => {
                    const img = createImg(event.target.result, i);
                    output.appendChild(img);
                    photos.push(file);
                    if (button.disabled === true) {
                        button.disabled = false;
                        button.classList.remove('btn-disabled');
                    }
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
        });
    } else {
        console.log("Your browser does not support File API");
    }
}

function createImg(src, i) {
    const rdiv = document.createElement("div");
    rdiv.className = 'radios';
    rdiv.appendChild(document.createTextNode(' Kes näevad:'));
    rdiv.appendChild(radio(0, 'ainult mina', i));
    rdiv.appendChild(radio(1, 'kasutajad', i));
    rdiv.appendChild(radio(2, 'kõik', i));

    const inp = document.createElement("input");
    inp.type = 'text';
    inp.name = 'title[]';
    inp.className = 'img-title';
    inp.placeholder = 'pildi nimi';

    const img = document.createElement("img");
    img.title = img.alt = src.name;
    img.src = src;
    img.className = 'thumbnail';

    const div = document.createElement("div");
    const div2 = document.createElement("div");
    div.appendChild(img);
    div2.appendChild(rdiv);
    div2.appendChild(inp);
    div.appendChild(div2);
    return div;
}

function radio(value, name, i) {
    const label = document.createElement("label");
    const radio = document.createElement("input");
    radio.type = 'radio';
    radio.name = 'priva' + i;
    radio.value = value;
    if (value === 0) {
        radio.checked = true
    }
    label.appendChild(radio)
    label.appendChild(document.createTextNode(name));
    return label;
}

// Open the Modal
function openModal(src, id) {
    console.log(src);
    document.getElementById("slide").src = 'uploads/' + src;
    document.getElementById("myModal").style.display = "block";
    const xhr = new XMLHttpRequest();
    xhr.open("POST", 'viewCount.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        id: id
    }));
}

// Close the Modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}