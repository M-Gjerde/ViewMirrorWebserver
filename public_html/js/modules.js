function createNoteModule(position) {

    for (var i = 1; i < 6; i++) {
        let element = document.createElement("INPUT");
        element.setAttribute("class", "noteContainer");
        element.setAttribute("type", "text");
        element.setAttribute("id", i.toString() + "Note");
        element.setAttribute("contenteditable", "true");
        element.setAttribute("onClick", "drag(this, event);");
        element.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                sendNotes();
            }
        });
        document.getElementsByClassName("grid-item")[position].appendChild(element);
    }
}

function createChromeCasteModule(position) {
    let element = document.createElement("IMG");
    element.setAttribute("class", "chromeCastChannel");
    element.setAttribute("src", "img/Danish_TV_2.png");
    element.setAttribute("onClick", "drag(this, event);");
    document.getElementsByClassName("grid-item")[position].appendChild(element);
}


function createWeatherModule(position) {
    let element = document.createElement("IMG");
    element.setAttribute("class", "weatherModule");""
    element.setAttribute("id", position.toString());
    element.setAttribute("src", "img/weather-icon.png");
    element.setAttribute("onclick", "drag(this, event);");
    document.getElementsByClassName("grid-item")[position].appendChild(element);
}

function createTimeModule(position) {
    let time = new Date();
    let minutes = time.getMinutes();
    let hours = time.getHours();
    let element = document.createElement("P");
    element.setAttribute("class", "time");

    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    element.innerHTML = hours + ": " + minutes;

    document.getElementsByClassName("grid-item")[position].appendChild(element);
}

function createFillerDiv(position) {
    let div = document.createElement("DIV");
    div.setAttribute("class", "fillerDiv");
    div.setAttribute("onclick", "drag(this,event);");
    document.getElementsByClassName("grid-item")[position].appendChild(div);
}

function createPopupNavBar(gridItemId) {
    let div = document.createElement("UL");
    div.setAttribute("id", "dropdown");
    div.setAttribute("class", "dropdownContent");
    let option1 = document.createElement("LI");
    let option2 = document.createElement("LI");
    let option3 = document.createElement("LI");
    let option4 = document.createElement("LI");
    let option5 = document.createElement("LI");
    //option1.setAttribute("href", "");
    option1.setAttribute("onclick", "changeModulePosition(event)");
    option2.setAttribute("onclick", "changeModulePosition(event)");
    option3.setAttribute("onclick", "changeModulePosition(event)");
    option4.setAttribute("onclick", "changeModulePosition(event)");
    option5.setAttribute("onclick", "changeModulePosition(event)");

    option1.innerHTML = "Videostreaming";
    option2.innerHTML = "Weather";
    option3.innerHTML = "Time";
    option4.innerHTML = "notes";
    option5.innerHTML = "Opdater spejl"

    div.appendChild(option1);
    div.appendChild(option2);
    div.appendChild(option3);
    div.appendChild(option4);
    div.appendChild(option5);

    document.getElementById(gridItemId).appendChild(div);
}

function updateTimeModule() {
    let timeNode = document.getElementsByClassName("time");

    let time = new Date();
    let minutes = time.getMinutes();
    let hours = time.getHours();

    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (hours < 10) {
        hours = "0" + hours;
    }

    for (i = 0; i < timeNode.length; i++) {
        timeNode[i].innerHTML = hours + ": " + minutes;
    }

}

function updateNoteModule() {
    let jsonRequest = new XMLHttpRequest();
    jsonRequest.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let jsonObj = JSON.parse(this.responseText);
            try {
                document.getElementById("1Note").value = jsonObj[jsonObj.length - 1].note1;
                document.getElementById("2Note").value = jsonObj[jsonObj.length - 1].note2;
                document.getElementById("3Note").value = jsonObj[jsonObj.length - 1].note3;
                document.getElementById("4Note").value = jsonObj[jsonObj.length - 1].note4;
                document.getElementById("5Note").value = jsonObj[jsonObj.length - 1].note5;
            } catch (e) {
                console.log(e);
            }
        }
    };
    jsonRequest.open('POST', 'https://app.kaufmanndesigns.net/db/showMessages.php', true);
    jsonRequest.send();
}

function sendNotes() {
    let https = new XMLHttpRequest();
    let form1 = document.getElementById("1Note").value.toString();
    let form2 = document.getElementById("2Note").value.toString();
    let form3 = document.getElementById("3Note").value.toString();
    let form4 = document.getElementById("4Note").value.toString();
    let form5 = document.getElementById("5Note").value.toString();
    let data = {
        memo1: form1,
        memo2: form2,
        memo3: form3,
        memo4: form4,
        memo5: form5
    };
    let params = typeof data == 'string' ? data : Object.keys(data).map(
        function(k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
        }
    ).join('&');
    https.open('POST', 'https://app.kaufmanndesigns.net/db/insertMessage.php/', true);
    https.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    https.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    https.send(params);
}

function sendOptions(options){
  let https = new XMLHttpRequest();
  let data = {
    gridArea1: options[0],
    gridArea2: options[1],
    gridArea3: options[2],
    gridArea4: options[3],
    gridArea5: options[4],
    gridArea6: options[5],
    gridArea7: options[6],
    gridArea8: options[7],
    gridArea9: options[8]
  };

  let params = typeof data == 'string' ? data : Object.keys(data).map(
      function(k) {
          return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
      }
  ).join('&');

  https.open("POST",  'https://app.kaufmanndesigns.net/db/insertMessage.php/', true);
  https.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  https.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  https.send(params);

}

function drag(elementToDrag, event) {

    if (document.getElementById("dropdown") != null) {
        document.getElementById("dropdown").parentNode.removeChild(document.getElementById("dropdown"));
    } else {
        createPopupNavBar(elementToDrag.getAttributeNode("id").value);
    }

}



function changeModulePosition(event) { //grid item remove child
    let gridItem = event.target.parentNode.parentNode;
    if (event.target.innerHTML == "Weather") {
        if (document.getElementsByClassName("weatherModule").length > 0) {
            document.getElementsByClassName("weatherModule")[0].parentNode.removeChild(document.getElementsByClassName("weatherModule")[0]);
        }
        let position = gridItem.getAttributeNode("id").value.replace(/[id]/gmi, "");
        createWeatherModule(parseInt(position));
    }
    if (event.target.innerHTML == "Time") {
      if (document.getElementsByClassName("time").length > 0){
        document.getElementsByClassName("time")[0].parentNode.removeChild(document.getElementsByClassName("time")[0]);
      }
        let position = gridItem.getAttributeNode("id").value.replace(/[id]/gmi, "");
        createTimeModule(parseInt(position));
    }

    if (event.target.innerHTML == "Videostreaming") {
      if (document.getElementsByClassName("chromeCastChannel").length > 0){
        document.getElementsByClassName("chromeCastChannel")[0].parentNode.removeChild(document.getElementsByClassName("chromeCastChannel")[0]);
      }
        let position = gridItem.getAttributeNode("id").value.replace(/[id]/gmi, "");
        createChromeCasteModule(parseInt(position));
    }

    if (event.target.innerHTML == "Opdater spejl") {
      //ALWAYS SEND UPDATES
      saveOptions();
      console.log("Finished... Sending Updates to mirror");
    }

}

function saveOptions() {
  let options =  [];
        let gridArray = document.getElementsByClassName("grid-item");
        for (let i = 0; i < gridArray.length; i++) {
            if (gridArray[i].children.length > 0) {
              if (gridArray[i].childNodes.length > 1){
              }
              for (let j = 0; j < gridArray[i].childNodes.length; j++){
                options[i] = gridArray[i].childNodes[j].getAttributeNode("class").value;
                if (gridArray[i].childNodes[j].getAttributeNode("class").value == "dropdownContent"){
                  options[i] = "";
                }
                console.log(gridArray[i].childNodes[j].getAttributeNode("class").value);

              }
            }
        }

        for (let i = 0; i < 9; i++) {
          if ( typeof options[i] === "undefined"){
            options[i] = ""
          }
        }
    sendOptions(options);
}

function touchStart(e) {
    console.log(e.target);

}

function touchEnd(e) {
    console.log(e.target);
}
