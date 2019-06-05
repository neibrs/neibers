(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.page_header= {
    attach: function attach(context) {
      console.log('page header js.');
      // for header js.
      function changenum(rec_id, diff, warehouse_id, area_id)
      {
        var cValue = $('#cart_value').val();
        var goods_number =Number($('#goods_number_' + rec_id).text()) + Number(diff);

        if(goods_number < 1)
        {
          return false;
        }
        else
        {
          change_goods_number(rec_id,goods_number, warehouse_id, area_id, cValue);
        }
      }
      function change_goods_number(rec_id, goods_number, warehouse_id, area_id, cValue)
      {
        if(cValue != '' || cValue == 'undefined'){
          var cValue = $('#cart_value').val();
        }
        Ajax.call('flow.php?step=ajax_update_cart', 'rec_id=' + rec_id +'&goods_number=' + goods_number +'&cValue=' + cValue +'&warehouse_id=' + warehouse_id +'&area_id=' + area_id, change_goods_number_response, 'POST','JSON');
      }
      function change_goods_number_response(result)
      {
        var rec_id = result.rec_id;
        if (result.error == 0)
        {
          $('#goods_number_' +rec_id).val(result.goods_number);//更新数量
          $('#goods_subtotal_' +rec_id).html(result.goods_subtotal);//更新小计
          if (result.goods_number <= 0)
          {
            //数量为零则隐藏所在行
            $('#tr_goods_' +rec_id).style.display = 'none';
            $('#tr_goods_' +rec_id).innerHTML = '';
          }
          $('#total_desc').html(result.flow_info);//更新合计
          if($('ECS_CARTINFO'))
          {//更新购物车数量
            $('#ECS_CARTINFO').html(result.cart_info);
          }

          if(result.group.length > 0){
            for(var i=0; i<result.group.length; i++){
              $("#" + result.group[i].rec_group).html(result.group[i].rec_group_number);//配件商品数量
              $("#" + result.group[i].rec_group_talId).html(result.group[i].rec_group_subtotal);//配件商品金额
            }
          }

          $("#goods_price_" + rec_id).html(result.goods_price);
          $(".cart_num").html(result.subtotal_number);
        }
        else if (result.message != '')
        {
          $('#goods_number_' +rec_id).val(result.cart_Num);//更新数量
          alert(result.message);
        }
      }

      function deleteCartGoods(rec_id,index)
      {
        Ajax.call('delete_cart_goods.php', 'id='+rec_id+'&index='+index, deleteCartGoodsResponse, 'POST', 'JSON');
      }

      /**
       * 接收返回的信息
       */
      function deleteCartGoodsResponse(res)
      {
        if (res.error)
        {
          alert(res.err_msg);
        }
        else if(res.index==1)
        {
          Ajax.call('get_ajax_content.php?act=get_content', 'data_type=cart_list', return_cart_list, 'POST', 'JSON');
        }
        else
        {
          $("#ECS_CARTINFO").html(res.content);
          $(".cart_num").html(res.cart_num);
        }
      }

      function return_cart_list(result)
      {
        $(".cart_num").html(result.cart_num);
        $(".pop_panel").html(result.content);
        tbplHeigth();
      }


      // End js for page header.
    }
  };
})(jQuery, Drupal, drupalSettings);

// For header js.
