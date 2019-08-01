function showStoreProducts(){
    $('#reload_product_btn').prop("disabled", true);

    let user_id = $('#user_id').val();
    $('#product_list').empty();
    $.ajax({
        type: 'GET',
        url: '/api/product',
        data: {
            user: user_id
        },
        dataType: 'json',
    })
    .done( (res) => {
        if(res['product_list'].length==0){
            $('#product_list').append('<tr><td>データが登録されていません</td></tr>');
        }
        for(let i=0; i<res['product_list'].length; i++){
            // TODOもっといい書き方を探す
            let apend_tr = '<tr>';
            apend_tr += '<td>'+(i)+'</td>'
            if(res['product_list'][i]['img_url'] == null){
                apend_tr += '<td>なし</td>'
            }else{
                apend_tr += '<td><img src='+res['product_list'][i]['img_url']+'></td>'
            }
            apend_tr += '<td>'+res['product_list'][i]['product_name']+'</td>'
            apend_tr += '<td>'+res['product_list'][i]['price']+'</td>'
            apend_tr += '<td>'+res['product_list'][i]['discription']+'</td>'
            apend_tr += '<td><button class="btn btn-danger" onclick="deleteProducts('+res['product_list'][i]['product_id']+')">削除</button></td>'
            apend_tr += '</tr>';
            $('#product_list').append(apend_tr);
        }
    })
    .fail( (err) => {
        alert("データ取得時にエラーが発生しました");
    })
    .always(()=>{
        $('#reload_product_btn').prop("disabled", false);
    })
}



function deleteProducts(product_id){
    $('#product_list').empty();
    let token = $('#csrf').val();

    $.ajax({
        type: 'DELETE',
        url: '/api/product/'+product_id,
        data: {
            _token: token
        },
        dataType: 'json',
    })
    .done( (res) => {
        // 削除が成功したか
        if(res['complete_flag']){
            alert("削除しました");
        // TODOエラー内容を表示する
        }else{
            alert("削除時にエラーが発生しました");
        }
    })
    .fail( (err) => {
        alert("データ削除時にエラーが発生しました");
    })
    .always(()=>{
        showStoreProducts();
    })
}



$(function(){

    // ページ読み込み時にすでに登録されている商品を読み込む
    showStoreProducts();

    $('#register_product_btn').on('click',function(){
        //二重登録防止
        $('#register_product_btn').prop("disabled", true);

        let token = $('#csrf').val();
        let img_file = $('#productImg').prop("files")[0];
        let product_name = $('#product_name').val();
        let product_price = $('#product_price').val();
        let product_discription = $('#product_discription').val();
        let formData = new FormData();
        formData.append('_token', token);
        formData.append('img_file', img_file);
        formData.append('product_name', product_name);
        formData.append('product_discription', product_discription);
        formData.append('product_price', product_price);

        $.ajax({
            url:'/api/product/',
            type:'POST',
            dataType: 'json',
            data:formData,
            processData: false,
            contentType: false
        })
        .done( (res) => {
            // DBへの登録が成功したか
            if(res['complete_flag']){
                alert("登録しました");
                clearForm($('#new_product'));
                showStoreProducts();
        // TODOエラー内容を表示する
            }else{
                alert("登録時にエラーが発生しました");
            }
        })
        .fail( (err) => {
            alert("登録時にエラーが発生しました");
        })
        .always(()=>{
            $('#register_product_btn').prop("disabled", false);
        })
    });

    function clearForm (form) {
        $(form)
            .find("input, select, textarea")
            .not(":button, :submit, :reset, :hidden")
            .val("")
            .prop("checked", false)
            .prop("selected", false)
        ;
        $(form).find(":radio").filter("[data-default]").prop("checked", true);
    }
});

