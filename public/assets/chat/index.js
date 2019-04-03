document.getElementById("message").focus();

let monPseudo = 'MonPseudo'; //<?php echo json_encode($_POST["pseudo"]);?>;
console.log(monPseudo)
function onKeyEnter(event) {
    if (event.keyCode === 13) {
        input = document.getElementById("message");

        var msg = {
            type: "message",
            text: input.value,
            id: monPseudo,
        };

        conn.send(JSON.stringify(msg));
        input.value = '';
    }
}

var conn = new WebSocket('ws://51.77.200.226:8080');
conn.onopen = function (e) {
    console.log("Connexion établie!");
};

conn.onmessage = function (e) {
    console.log(e.data)
    obj = JSON.parse(e.data)
    addElementMessage(obj.fromMyself, obj.id, obj.message);
};

function addElementMessage(fromMyself, id, message) {
    var newDiv = document.createElement("p");
    fromMyself === 1 ? newDiv.classList.add("user-message") : newDiv.classList.add("not-user-message");
    var balise = newDiv.appendChild(document.createElement("span"))
    fromMyself === 1 ? balise.style.color = "orange" : balise.style.color = "red";
    balise.style.fontWeight = "bold";
    balise.style.textDecoration = "underline";
    balise.appendChild(document.createTextNode(id + " :"));
    newDiv.appendChild(document.createElement("br"))
    var newContent = document.createTextNode(message);
    newDiv.appendChild(newContent);
    // ajoute le nouvel élément créé et son contenu dans le DOM 
    var currentDiv = document.getElementById("chat-content");
    currentDiv.append(newDiv);
    // bloque le scroll en bas
    currentDiv.scrollTop = currentDiv.scrollHeight;

    document.getElementById("message").focus();
}