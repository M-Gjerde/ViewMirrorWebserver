$ = el => document.querySelector(el);


class cssGRID {

    constructor(modules) {
        for (let i = 0; i < modules; i++) {
          let id = "gridNumber" + i.toString();

            this.add();
        }

        //Add ID name to all grid gridItems

        for (let i = 0; i < document.getElementsByClassName("grid-item").length; i++){
          let element = document.getElementsByClassName("grid-item")[i];
          element.setAttribute("id", "id" + i.toString());
          element.setAttribute("onClick", "drag(this, event);");
        }
    }

    add() {
        document.getElementById("wrapper").innerHTML += '<div class="grid-item"></div>';
        this.updateVar();
    }

    updateVar() {
        let htmlStyles = window.getComputedStyle($("html"));
        let rowNum = parseInt(htmlStyles.getPropertyValue("--rowNum"));
        let colNum = parseInt(htmlStyles.getPropertyValue("--colNum"));
        let gridItemsCount = document.querySelectorAll(".grid-item").length;
        document.documentElement.style.setProperty(`--rowNum`, Math.ceil(gridItemsCount / colNum));


    }

    changeCol(action) {
        let htmlStyles = window.getComputedStyle($("html"));
        let colNum = parseInt(htmlStyles.getPropertyValue("--colNum"));
        if (action === 'add') {
            document.documentElement.style.setProperty(`--colNum`, colNum + 1);
        } else {
            document.documentElement.style.setProperty(`--colNum`, colNum - 1);
        }
        self.updateVar();


    }

    addRule(name, rules) {
        let style = document.createElement("style");
        style.type = "text/css";
        document.getElementsByTagName("head")[0].appendChild(style);
        if (!(style.sheet || {}).insertRule)
            (style.styleSheet || style.sheet).addRule(name, rules);
        else {
            style.sheet.insertRule(name + "{" + rules + "}", 0);
        }
    }


    loadPreviousSettings(){
      let string = "area1";
      let jsonRequest = new XMLHttpRequest();
      jsonRequest.onreadystatechange = function() {
          if (this.readyState === 4 && this.status === 200) {
              let jsonObj = JSON.parse(this.responseText);
              try {

                let options = jsonObj[jsonObj.length - 1]
                for( let prop in options ){
                  console.log(options[prop]);

                  let position = prop.replace(/area/gmi, "") - 1;

                  if (options[prop] == "weatherModule"){
                    createWeatherModule(position);
                  } else if(options[prop] == "chromeCastChannel"){
                    createChromeCasteModule(position);
                  } else if(options[prop] =="time"){
                    createTimeModule(position);
                  }
                }


              } catch (e) {
                console.log(e);
              }
          }
      };
      jsonRequest.open('POST', 'https://app.kaufmanndesigns.net/db/showOptions.php', true);
      jsonRequest.send();
    }




}



function init() {
    let element = new cssGRID(9);
    element.addRule(".grid-item", "background-color: black;");

    //createNoteModule(0, 5);
    //let noteModule = document.getElementsByClassName("grid-item")[0].children[0].getAttributeNode("class").value;
  //  element.addRule("." + noteModule, "color: blue; font-size: 1.5em; letter-spacing: 0.1em; line-height: 2em; ");
    //updateNoteModule(); //run once to set placeholders

    //createChromeCasteModule(3);
    //let chromeCastModule = document.getElementsByClassName("grid-item")[3].children[0].getAttributeNode("class").value;
    //element.addRule("." + chromeCastModule, "max-width:100%;height:auto;")

  //  createWeatherModule(4);
    //let weatherModule = document.getElementsByClassName("grid-item")[4].children[0].getAttributeNode("class").value;
  //  element.addRule("." + weatherModule,  "max-width:100%;height:auto;");

  setInterval(function(){
    updateTimeModule();
  }, 5000);
  element.loadPreviousSettings();


}


init();
