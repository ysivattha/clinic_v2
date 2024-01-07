<?php
// check permission
function check($permission_name, $action) 
{
    $role_id = Auth::user()->role_id;
    $q = DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->select('role_permissions.list','role_permissions.insert','role_permissions.delete','role_permissions.update')
            ->where(['role_permissions'.'.role_id' => $role_id, 'permissions.name' => $permission_name]);

    switch ($action) {
        case 'i':
            $q = $q->where('role_permissions.insert', 1);
            break;
        case 'u':
            $q = $q->where('role_permissions.update', 1);
            break;
        case 'd':
            $q = $q->where('role_permissions.delete', 1);
            break;
        case 'l':
            $q = $q->where('role_permissions.list', 1);
            break;	
        default:
            break;
    }
   
       return $q->count() > 0;
}
// function to return button edit and delete
function btn_actions($id, $tbl, $per)
{
    $del = '';
    $edit = '';
    if(check($per, 'd'))
    {
        $del = "<button type='button' onclick='remove({$id}, this)' table='{$tbl}' per='{$per}' 
            class='btn btn-danger btn-xs' title='Delete'>
            <i class='fa fa-trash'></i>
        </button>";
    }
    if(check($per, 'u'))
    {
        $edit = "<button type='button' onclick='edit({$id}, this)' table='{$tbl}' per='{$per}' data-toggle='modal'  
            data-target='#editModal' class='btn btn-success btn-xs mr-1' title='Edit'>
            <i class='fa fa-edit'></i>
        </button>";
    }
    return $edit . $del;
}