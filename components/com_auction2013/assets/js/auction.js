window.onload=function(){
    //console.log("Auction is here!");
    var boxes=[], texts = {}, maxHeights = {},
        boxesObjects = ['.box >h2','.box >.product_s_desc'] /*{
            headerObjects: {
                box:document.querySelectorAll('.box >h2'),
                maxHeight:box.offsetHeight
            },
            descObjects: {
                box:document.querySelectorAll('.box >.product_s_desc')
            }
        }*/;
    /*var maxHeightHeader = boxesObjects.headerObjects[0].offsetHeight,
        maxHeightDesc = boxesObjects.descObjects[0].offsetHeight;*/
    for(var o = 0, j=boxesObjects.length; o<j; o++){
        //console.groupCollapsed(boxesObjects[o]);
        // получить селекторы заголовков и описаний
        boxes[o]=document.querySelectorAll(boxesObjects[o]);
        // получить параметр высоты, выбрав из первого селектора каждого типа
        maxHeights[boxesObjects[o]]=boxes[o][0].offsetHeight; //  maxHeights['.box >h2']...
        //console.dir(boxes[o]);
        texts[boxesObjects[o]]=[]; // контейнер для текстов (заголовок, описание)
        for (var i in boxes[o]) {
            if (typeof boxes[o][i] === 'object') {
                texts[boxesObjects[o]][i] = boxes[o][i].textContent.split(" ");
                //console.dir(texts[boxesObjects[o]][i]);
            }
        }
        //console.groupEnd();
    }
    //console.log('maxHeights: '); console.dir(maxHeights);
    //console.log('boxes: '); console.dir(boxes);
    //console.log('texts: '); console.dir(texts);

    var handleText = function(o, i, textArray) {

        var newTextArray;
        /* Note: use just .textContent property, NOT .innerText! */
        boxes[o][i].textContent = textArray.join(" ");

        if (maxHeights[boxesObjects[o]] < boxes[o][i].scrollHeight) {
            //console.log(o+", "+i+": %cОбрезать строку","color:red");
            newTextArray = textArray.slice(0, textArray.length - 1);
            handleText(o, i, newTextArray);
        }
    };

    var handleBox = function() {
        var overflow;
        for(var o = 0,j=boxes.length; o<j; o++) {
            //console.group('boxes['+o+']');
            //console.dir(boxes[o]);
            for (var i= 0, tl = texts[boxesObjects[o]].length; i<tl; i++) {
                //console.log('i='+i);
                //console.dir(texts[boxesObjects[o]][i]);
                handleText(o, i, texts[boxesObjects[o]][i]);
                var blockTextArray = boxes[o][i].textContent.split(" "),
                    heightDown = maxHeights[boxesObjects[o]] < boxes[o][i].scrollHeight,
                    lengthsDiff = blockTextArray.length < texts[boxesObjects[o]][i].length;
                overflow=false;
                //boxes[o][i].style.background = "";
                if (heightDown || lengthsDiff) {
                    overflow=true;
                    /*if (heightDown && lengthsDiff) {
                        boxes[o][i].style.background = "pink";
                    } else {
                        boxes[o][i].style.background =(heightDown)?
                            "lightskyblue" : "lightgreen";
                    }
                } else {
                    boxes[o][i].style.background = "";*/
                }   //console.log('overflow: '+overflow);
                if(overflow){
                    //pds[4].innerHTML=pds[4].textContent.substr(0,pds[4].innerHTML.length-2)+"..."
                    boxes[o][i].innerHTML=boxes[o][i].textContent.substr(0,boxes[o][i].innerHTML.length-2)+"...";
                }
            }   //console.groupEnd();
        }
    };

    function testIt(){
        console.log('Testing...');
    }
    window.onresize = handleBox;
    handleBox();
};