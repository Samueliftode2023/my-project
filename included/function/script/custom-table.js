var elementSel = 'pagina-planificare'
var preventSign = 0
var foaieTabelJson = {
    "pagina":{
        "overflow": "auto",
        "position": "absolute",
        "width": "100%",
        "height": "100%",
        "padding-left": 0,
        "padding-right": 0,
        "padding-top": 0,
        "padding-bottom": 0,
        "background": "white",
        "display": "grid",
        "grid-template-columns": "repeat(2,50%)",
        "grid-template-rows": "repeat(2,50%)",
        "box-shadow": "0px 0px 2px black"
    },
    "elemente":{
        "tabele":[
            
        ]
    }
}


function openPanelCreate(){
    $('#back-panel').toggleClass('dis-none')
}

function createTable(dateForm){
    if(preventSign == 0){
        preventSign = 1
        $('#loading-id').removeClass('dis-none')
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/custom-table.php",
            data: dateForm,
            contentType: false,
            processData: false,
            success: function (result) {
                if(result === 'ok'){
                    location.reload();
                }
                else{
                    alert(result)
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 1000);
            }
        })
    }
}

function readJsonTable(dateForm){
    if(preventSign == 0){
        preventSign = 1
        $('#loading-id').removeClass('dis-none')
        $.ajax({
            type: "POST",
            url: "../../../included/function/exe/custom-table.php",
            data: dateForm,
            contentType: false,
            processData: false,
            success: function (result) {
                if(result != 0){
                    foaieTabelJson = result
                }
                else{
                    createPageTable(foaieTabelJson)
                }
                preventSign = 0
                setTimeout(function() {
                    $('#loading-id').addClass('dis-none');
                }, 1000);
            }
        })
    }
}
// READY FUNCTII PRESTABILITE
    $(document).ready(function() {
        // FUNCTIE PENTRU EDITAREA MARGINILOR
            $('#custom-padding input').change(function() {
                var pagina = document.getElementById('pagina-planificare');
                var valoareInput = $(this).val();
                var paddingType = $(this).attr('id').split('-');
            
                var paddingSide = paddingType[2];
                var oppositeSide = {
                    'right': 'left',
                    'left': 'right',
                    'top': 'bottom',
                    'bottom': 'top'
                }[paddingSide];
            
                var propertyToUpdate = paddingType[1];
            
                // Actualizează obiectul JSON
                foaieTabelJson['pagina']['padding-' + paddingSide] = valoareInput;
            
                var newValue = "calc(100% - " + (
                    (+foaieTabelJson['pagina']['padding-' + paddingSide]) + 
                    (+foaieTabelJson['pagina']['padding-' + oppositeSide] || 0)
                ) + "px)";
            
                foaieTabelJson['pagina'][propertyToUpdate] = newValue;
            
                // Aplică stilurile
                pagina.style['padding-' + paddingSide] = valoareInput + "px";
                pagina.style[propertyToUpdate] = newValue;
            });
        //
        // FUNCTIE PENTRU EDITARE GRID
            $('#custom-grid input').change(function(){
                var pagina = document.getElementById('pagina-planificare')
                var valoareInput = $(this).val()
                var idInput = $(this).attr('id').split('-')[2]

                if(valoareInput > 0){
                    var gridNum = 100 / valoareInput + '%'
                    foaieTabelJson['pagina']['grid-template-' + idInput] = 'repeat(' + valoareInput + ', ' + gridNum + ')'
                    pagina.style['grid-template-' + idInput] = foaieTabelJson['pagina']['grid-template-' + idInput]
                }
                else if(valoareInput == 0){
                    foaieTabelJson['pagina']['grid-template-' + idInput] = 'none'
                    pagina.style['grid-template-' + idInput] = 'none'
                }
            })
        //

        $('#create-table-id').submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            createTable(formData)
        })
    });
//

// FUNCTIE DE CREERE A TABELELOR
    function createPageTable(foaieTabelJson){
        var contentJson = document.getElementById('content-json')
        var newElement = document.createElement('div')
        newElement.setAttribute('id','pagina-planificare')
        
        Object.entries(foaieTabelJson['pagina']).forEach(([cheie, valoare]) => {
            newElement.style[cheie] = valoare
        });

        contentJson.appendChild(newElement)
    }

    function editTable(el){
        var idElement = el.id
        var formData = new FormData();
        $('#start-panel').addClass('dis-none')
        $('#tools-content-json').removeClass('dis-none')

        formData.append('structura', idElement)
        readJsonTable(formData)
    }
//

// FUNCTIE PENTRU EDITAREA MARGINILOR
    function customPadding(){
        $('#custom-padding').toggleClass('dis-none')
        var paddingArray = ['width-left','width-right','height-top','height-bottom']

        for (let index = 0; index < paddingArray.length; index++) {
            $('#custom-' + paddingArray[index]).val(foaieTabelJson['pagina']['padding-' + paddingArray[index].split("-")[1]])
        }
    }
//

// CUSTOM GRID
function customGrid() {
    $('#custom-grid').toggleClass('dis-none');
    let paginaGrid = getComputedStyle(document.getElementById('pagina-planificare'));
    $('#custom-grid-rows').val(paginaGrid.getPropertyValue("grid-template-rows").split(" ").length);
    $('#custom-grid-columns').val(paginaGrid.getPropertyValue("grid-template-columns").split(" ").length);
}
//

// TABLE
    function addTable(){
        let idTable = foaieTabelJson['elemente']['tabele'].length

        foaieTabelJson['elemente']['tabele'].push({
            'parent': elementSel,
            'id': 'tabel-' + idTable,
            'position': 'absolute',
            'width': '50%',
            'box-shadow': '0px 0px 1px black',
            'display': 'grid',
            'grid-template-columns': 'repeat(2,50%)',
            'grid-template-rows': 'repeat(6,40px)',
            'cell':{
                'num': 12, 
                'style': 'height: 40px; border: 0.5px solid black; width:calc(100% - 1px)'
            }
        })
        readTables(foaieTabelJson['elemente']['tabele'])
    }

    function readTables(table){
        for (let index = 0; index < table.length; index++) {
            let createTable = document.createElement('div')
            Object.entries(table[index]).forEach(([cheie, valoare]) => {
                if(cheie != 'cell' && cheie != 'parent'){
                    if(cheie != 'id'){
                        createTable.style[cheie] = valoare
                    }
                    else{
                        createTable.id = valoare
                    }
                }   
                else if(cheie == 'cell'){
                    let createCell = () => {
                        var divCell = "<div style='" + table[index]['cell']['style'] + "'></div>"
                        return divCell.repeat(table[index]['cell']['num'])
                    }
                    createTable.innerHTML = createCell()
                } 
            });       
            document.getElementById(table[index]['parent']).appendChild(createTable)

            let manageElements = document.getElementById('manage-elements');
            let elementDiv = document.createElement('div');
            elementDiv.id = "element-" + table[index]['id'];
            elementDiv.className = 'element-style';
            elementDiv.textContent = table[index]['id'];
            elementDiv.onclick = function () { selectThisElement(this); };

            if(!document.getElementById("element-" + table[index]['id'])){
                manageElements.appendChild(elementDiv);
            }
        }        
    }
    function editCell(){
        if(elementSel.split('-')[0] == 'tabel'){
            alert()
        }
    }
//

// FUNCTIE DE SELECTARE A ELEMENTELOR
    function selectThisElement(el){
        document.getElementById('element-' + elementSel).classList.remove('select-element')
        elementSel = el.id.split('element-')[1]
        document.getElementById('element-' + elementSel).classList.add('select-element')
        if(el.id.split('-')[1] == 'tabel'){
            document.getElementById('edit-cell-buton').classList.remove('disbaled-class')
        }
        else{
            document.getElementById('edit-cell-buton').classList.add('disbaled-class')
        }
    }
//