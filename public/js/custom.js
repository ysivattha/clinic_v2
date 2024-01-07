// checkbox on datatable function
function check(obj)
{
    let check = $(obj).prop('checked');
    let tds = $('.datatable tbody tr td:first-child input');
    if(check)
    {
        
        for(let i=0; i<tds.length; i++)
        {
            $(tds[i]).prop('checked', true);
        }
    }
    else{
        for(let i=0; i<tds.length; i++)
        {
            $(tds[i]).prop('checked', false);
        }
    }
}
// function for bulk delete
$("#btnDelete").on('click', function(event)
{
    let con = confirm('You want to delete the selected records?');
    if(con)
    {
        let tbl = $(event.target).attr('table');
        let per = $(event.target).attr('permission');
        let token = $(event.target).attr('token');
        let tds = $('.datatable tbody tr td:first-child input:checked');
        let arr = [];
        for(let i=0; i<tds.length; i++)
        {
            let id = $(tds[i]).val();
            arr.push(id);
        }
        let data = {
            tbl: tbl,
            per: per,
            ids: arr
        };
        $.ajax({
            type: 'POST', 
            url: burl + '/bulk/remove',
            data: data,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(sms)
            {
                $('#dataTable').DataTable().ajax.reload();
            }
        });
       
    }
});
// function for delete button
function remove(id, obj)
{
    let con = confirm('You want to delete this record?');
    let tbl = $(obj).attr('table');
    let per = $(obj).attr('per');
    if(con)
    {
        $.ajax({
            type: 'GET', 
            url: burl + '/bulk/delete/' + id + '?tbl=' + tbl + '&per=' + per,
            success: function(sms)
            {
                $('#dataTable').DataTable().ajax.reload();
            }
        });
    }
}
// function to save data from a form
function frm_submit(evt)
{
    evt.preventDefault();
    let data = $(evt.target).serialize();
    $.ajax({
        type: 'POST',
        url: burl + '/bulk/save',
        data: data,
        dataType: 'json',
        success: function(sms){
            if(sms>0)
            {
                let txt = `<div class='alert alert-success p-2' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <div>
                        Data has been saved successfully!
                    </div>
                </div>`;
                $('#sms').html(txt);
                $("#create_form")[0].reset();
                $('#dataTable').DataTable().ajax.reload();
                // update all chosen select
                $('#create_form .chosen-select').trigger("chosen:updated");
            }
            else{
                let txt = `<div class='alert alert-danger p-2' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <div>
                        Fail to save data, please check again!
                    </div>
                </div>
                `;
                $('#sms').html(txt);
            }
            
        }
    });
}
// function to update data from a form
function frm_update(evt)
{
    evt.preventDefault();
    let data = $(evt.target).serialize();
    $.ajax({
        type: 'POST',
        url: burl + '/bulk/update',
        data: data,
        dataType: 'json',
        success: function(sms){
            if(sms>0)
            {
                
                $("#edit_form")[0].reset();
                $('#dataTable').DataTable().ajax.reload();
                $('#editModal').modal('hide');
            }
            else{
                let txt = `<div class='alert alert-danger p-2' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <div>
                        Fail to save data, please check again!
                    </div>
                </div>
                `;
                $('#esms').html(txt);
            }
            
        }
    });
}
function reset(id)
{
    $(id).trigger('reset');
}
