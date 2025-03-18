<div id='container'>
    <div class='bara-control'>
        <button class='disabled-class green-one' onclick='generateKey()'>Salveaza</button>
        <button id='remove-new-key' onclick='unsetNewKey()' class='disabled-class red-one'>Anuleaza</button>
    </div>
    <div class='container-content'>
        <div id='panou-de-lucru'>
            <div id='bara-pentru-selectat'>   
                <div class='selectat' id='add'>Adauga</div>
                <div onclick='listaVestitori("select")' id='edit'>Editeaza</div>
                <div onclick='listaVestitori("checkbox")' id='delete'>Sterge</div>
            </div>
            <div id='panou-editare'>
                <!--FORM PENTRU ADAUGAREA VESTITORILOR-->
                    <form id='add-vestitor'>
                        <h4>Adauga un vesitor</h4>
                        <input type='hidden' name='lista' value='add'>
                        <label>
                            Numele complet al vestitorului                  
                            <input id='full-name' name='full-name' type='text'>
                        </label>
                        <br>
                        <label>
                            Privelgiu
                            <select name='privilege'>
                                <option>Vestitor</option>
                                <option>Slujitor auxiliar</option>
                                <option>Batran</option>
                                <option>Vestitor nebotezat</option>
                                <option>Inscris doar la scoala</option>
                            </select>
                        </label>
                        <br>
                        <label>
                            Genul
                            <select name='genul'>
                                <option>Masculin</option>
                                <option>Feminin</option>
                            </select>
                        </label> 
                        <br>
                        <label>
                            <button>Adauga</button>
                        </label>
                    </form>
                <!---->
                <!--FORM PENTRU EDITAREA VESTITORULUI-->
                    <form id='edit-vestitor' class='dis-none'>
                        <input type='hidden' name='lista' value='edit'>
                        <h4>Editeaza un vesitor</h4>
                        <label id='vestitori-label'></label>
                        <br>
                        <label>
                            Modifica numele
                            <input type='text' id='new-name' name='full-name'>
                        </label>
                        <br>
                        <label>
                            Privelgiu
                            <select id='new-priv' name='privilege'>
                                <option>Vestitor</option>
                                <option>Slujitor auxiliar</option>
                                <option>Batran</option>
                                <option>Vestitor nebotezat</option>
                                <option>Inscris doar la scoala</option>
                            </select>
                        </label>
                        <br>
                        <label>
                            Genul
                            <select id='new-gen' name='genul'>
                                <option>Masculin</option>
                                <option>Feminin</option>
                            </select>
                        </label>
                        <br>
                        <label>
                            <button>Modifica</button>
                        </label>
                    </form>
                <!---->
                <!--FORM PENTRU STERGEREA VESTITORILOR-->                    
                    <form id='delete-vestitor' class='dis-none'>
                        <input type='hidden' name='lista' value='delete'>
                        <div id='sterge-vestitorii'></div>
                        <label>
                            <button>Sterge</button>
                        </label>
                    </form>
                <!---->                
            </div>
        </div>
        <div id='panou-de-vizualizat'>
            <div id='bara-pentru-lista'>   
                <div onclick='get_list("add")' class='selectat' id='vestitori-add'>Nou</div>
                <div onclick='get_list("edit")' id='vestitori-edit'>Modificat</div>
                <div onclick='get_list("delete")' id='vestitori-delete'>Sters</div>
            </div>
            <div id='panouri-vizuale'>
                <span class='tabel-liste'>
                    <span>Nume</span>
                    <span>Privilegiu</span>
                    <span>Gen</span>
                    <span></span>
                </span>
                <div id='lista-vestitori-add'></div>
                <div id='lista-vestitori-edit' class='dis-none'></div>
                <div id='lista-vestitori-delete' class='dis-none'></div>
            </div>
        </div>
    </div>
    <div id='loading-id' class='loading dis-none'>
        <div class='centrare-loading'>            
            <div class="loadingio-spinner-rolling-5owm4mbayhw"><div class="ldio-jl4i6909sug">
            <div></div>
            </div>
            </div>
        </div>
    </div>   
    <div id='id-back-panel' class='back-panel dis-none'></div>
    <div id='panel-download-key' class='dis-none'>
        <div onclick='closePanel()' id='close-panel'>
            <span class="material-symbols-outlined">
                close
            </span>
        </div>
        <div id='show-key' class='dis-none'></div>
        <button  onclick='copyCode()' id='id-copy' class='copy-button dis-none'>
            <span class="material-symbols-outlined">
            content_copy
            </span>
            <span>Copieaza</span>
        </button>
        <div id='butoane-gestionare-cheie' class='dis-none'>
            <button onclick='generateKey()'>
                <span class="material-symbols-outlined">
                    refresh
                </span>
                <span>Refresh</span>
            </button>
            <button onclick='downloadCode()'>
                <span class="material-symbols-outlined">
                    download
                </span>
                <span>Descarca</span>
            </button>
            <button onclick='copyCode()'>
                <span class="material-symbols-outlined">
                    content_copy
                </span>
                <span>Copiaza</span>
            </button>
        </div>
        <form id='zona-finish' class='dis-none'>
            <input onchange='showUpload()' id='file-key' class='dis-none' type="file" name="file"  accept=".json">
            <label id='mask-key' for='file-key'>
                <div class='togheter'>
                    <span class="material-symbols-outlined">
                        upload
                    </span>
                    <span id='show-charge'>
                        Incarca noua cheie
                    </span>
                </div>
            </label> 
            <button>Finalizeaza</button>
        </form>
    </div>
</div>
