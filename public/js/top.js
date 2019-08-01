function showStoreProducts(){
    $('#search_product_btn').prop("disabled", true);

    $('#product_list').empty();
    let name = $('#product_name').val();
    let product_price_min = $('#product_price_min').val();
    let product_price_max = $('#product_price_max').val();

    $.ajax({
        type: 'GET',
        url: '/api/product',
        data: {
            name:name,
            price_min:product_price_min,
            price_max:product_price_max,
        },
        dataType: 'json',
    })
    .done( (res) => {
        if(res['product_list'].length==0){
            $('#product_list').append('<tr><td>商品がありません</td></tr>');
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
            apend_tr += '</tr>';
            $('#product_list').append(apend_tr);
        }
    })
    .fail( (err) => {
        alert("データ取得時にエラーが発生しました");
    })
    .always(()=>{
        $('#search_product_btn').prop("disabled", false);
    })
}


function clearSerchForm () {
    let form = $('#serch_product');

    $(form)
        .find("input, select, textarea")
        .not(":button, :submit, :reset, :hidden")
        .val("")
        .prop("checked", false)
        .prop("selected", false)
    ;
    $(form).find(":radio").filter("[data-default]").prop("checked", true);
}

$(function(){
    // ページ読み込み時にすでに登録されている商品を読み込む
    showStoreProducts();
});

