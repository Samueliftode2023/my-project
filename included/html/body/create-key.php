<div id='container'>
    <div class='tab'>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vestitor</th>
                    <th>Privilegiu</th>
                    <th>Gen</th>
                </tr>
            </thead>
            <tbody id='tabel-locatie'>
            </tbody>
        </table>
    </div>
    <form id='vestitori'>
        <h2>Adauga un vestitor</h2>
        <label>
            Numele complet al vestitorului
            <input id='name-full' name='full-name' minlength='3' maxlength='50' required>
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
        <button>Adauga</button>
        <div id='finish' onclick='createJson(congregatie)' class='ready-download'>Finalizeaza</div>
    </form>
    <div id='loading-id' class='loading dis-none'>
        <div class='centrare-loading'>            
            <div class="loadingio-spinner-rolling-5owm4mbayhw"><div class="ldio-jl4i6909sug">
            <div></div>
            </div>
            </div>
        </div>
    </div>
    <div id='send-to-upload' class='dis-none'>
        <h3 style='text-align:center;'>Cheia a fost creata cu succes!</h3>
        <p>Cheia reprezinta un fisier json. Acest fisier este baza ta de date, asa ca tine-o intr-un loc sigur!</p>
        <details>
            <summary>Fisierul meu nu s-a descarcat.</summary>
            <h4>Daca au aparut probleme la descarcare urmeaza urmatorii pasi:</h4>
            <ol>
                <li>Copieaza codul de mai jos.</li>
                <div class='parent'><div id='code-source'></div><div id='copy-f' onclick='copyDivText()'>Copiaza</div></div>
                <li>Creeaza un fisier cu terminatia <span style='color:orange;'>'.json'.</span></li>
                <li>Lipeste codul copiat.</li>
                <li>Salveaza-l sub denumirea: <span id='name-file' style='color:orange;'></span></li>
            </ol>
        </details>
        <p>Ai gresit sau ai omis pe cineva? Nu te stresa, poti modifica cheia oricand!</p>
        <button class='up-load' onclick='uploadFile(preventSign)'>Incarca fisierul</button>
    </div>
</div>
<div id='display-message'>

</div>