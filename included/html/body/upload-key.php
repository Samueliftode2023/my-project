<div id="container">
    <div class='bara-control'>Pagina de incarcare a cheii</div>
    <form id='upload-key'>
        <h4>Cheia contului este:<span class='mark-word'>                     
            <?php 
                $sql_search = 'SELECT * FROM users WHERE username = "'.$_SESSION['username'].'"';
                $execute_search = mysqli_query($conectareDB, $sql_search);
                $row_user =  mysqli_fetch_assoc($execute_search);
                $filter_key = explode('.json',$row_user['key_user']);
                echo $filter_key[0];
            ?>.json</span>
        </h4>
        <label id='upload-label' for="file">
            <div>Alege un fisier .json</div>
            <span class="material-symbols-outlined">
                upload
            </span>
    </label>
        <input onchange='showUpload(this)' type="file" class='dis-none' name="file" id="file" accept=".json" required>
        <br>
        <button>Incarca </button>
    </form>
        <div id='loading-id' class='loading dis-none'>
        <div class='centrare-loading'>            
            <div class="loadingio-spinner-rolling-5owm4mbayhw"><div class="ldio-jl4i6909sug">
            <div></div>
            </div>
            </div>
        </div>
    </div>
</div>
<div id='display-message'>

</div>