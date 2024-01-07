function getRequest()
{
    let id = $("#request_id").val();
    var row;
    for(a=0; a<id.length; a++)
    {
        
        $.ajax({
            type: "GET",
            async: false,
            url : burl + "/invoice/get-request/" + id[a],
            success: function(sms)
            {

                let data = JSON.parse(sms);
                
                if(data!=null)
                {
                    let items = data.details;
                    
                    for(i=0; i<items.length; i++)
                    {
                        let percent2 = items[i].percent2;
                        let percent3 = items[i].percent3;
                        let request_note = items[i].request_note;
                        let sub_total = items[i].price - items[i].discount;
                        if(request_note==null) {
                            request_note = '';
                        } 
                        if(percent2 == null) {
                            percent2 = '';
                        }
                        if(percent3 == null) {
                            percent3 = '';
                        }
                        row +=`
                            <tr>
                                <td>
                                    <input type="hidden" value="${items[i].date}" name="request_date[]">
                                    <input type="hidden" value="${items[i].time}" name="request_time[]">
                                    <input type="hidden" value="${items[i].section_id}" name="section_id[]"> 
                                   <input type="hidden" value="${items[i].section_name}" name="section_name[]"> 
                                    <input type="hidden" value="${items[i].item_id}" name="item_id[]"> 
                                    <input type="hidden" value="${items[i].item_name}" name="item_name[]"> 
                                    <input type="hidden" value="${items[i].price}" name="price[]"> 
                                    <input type="hidden" value="${sub_total}" name="sub_total[]"> 
                                    <input type="hidden" value="${items[i].discount}" name="discount[]"> 
                                    <input type="hidden" value="${items[i].percent1}" name="percent1[]"> 
                                    <input type="hidden" value="${items[i].percent1_code}" name="percent1_code[]"> 
                                    <input type="hidden" value="${percent2}" name="percent2[]"> 
                                    <input type="hidden" value="${percent3}" name="percent3[]"> 
                                    <input type="hidden" value="${items[i].id}" name="request_detail_id[]"> 
                                    
                                    ${items[i].request_code}</td>
                                    <td>${items[i].section_name}</td>
                                <td>${items[i].item_name}</td>
                                <td>${items[i].price}</td>
                                <td>${items[i].discount}</td>
                                <td class="sub">${sub_total}</td>
                                
                            </tr>
                        `;
                    }
                   
                }
                
            }
        });
    }
    if(id.length==0)
    {
        $("#data").html("");
    }
    else{
        $("#data").html(row);
    }
    getTotal();
    $('#patiend_id').val("");
    $("#patiend_id").trigger("chosen:updated");
}