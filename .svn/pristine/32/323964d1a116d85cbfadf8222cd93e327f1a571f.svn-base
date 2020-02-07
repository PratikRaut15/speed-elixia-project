    <?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");

include("header.php");
?>
<style>
    table tr td{
        vertical-align: middle;
    }
</style>
<div class="panel">
    <div class="paneltitle" align="center">Add New Team Member</div>
    <div class="panelcontents">
        <form method="post" name="teamform" id="teamform">
            <?php echo($message); ?>
            <table width="100%">
                <tr>
                <td>Name <span style="color:red;">*</span></td><td><input id="tname" name = "tname" type="text" placeholder="Enter Name" required></td>
                <td>Phone <span style="color:red;">*</span></td><td><input name = "tphone" id="tphone" type="text" placeholder="Enter Phone" onkeypress="return onlyNos(event,this);" maxlength="12" required></td>
                </tr>
                <tr>
                <td>Email <span style="color:red;">*</span></td><td><input name = "temail" id="temail" type="text" placeholder="Enter Email" required></td>
                    <td>Login Role:</td>
                    <td> <select id="role" name="role">
                    <option id="Head" value="Head">Head</option>                
                    <option id="Admin" value="Admin">Admin</option>        
                    <option id="Service" value="Service">Service</option>  
                    <option id="Service" value="Sales">Sales</option>  
                    <option id="Service" value="CRM">CRM</option>  
                    <option id="Sourcing" value="Data">Data</option> 
                    <option id="Distributor" value="Distributor">Distributor</option> 
                    <option id="Repair" value="Repair">Repair</option> 
                    </select>
                  </tr> 
                  <tr>
                    <td>Department</td>
                    <td><select name="departmentId" id="departmentId" required>
                        </select></td>
                    <td>Comapny Role</td>
                    <td><select name="company_role" id="company_role" required>
                           <option value=0>Select Role</option>
                        </select></td>
                </tr> 
                  <tr><td>Member Type: </td><td><input type="radio" name="membertype" value="1" checked>Elixir <input type="radio" name="membertype" value="2"> Non - Elixir</td></tr>
                  
                <tr>
                <td>Login <span style="color:red;">*</span></td><td><input name = "tlogin" id="tlogin" type="text"  placeholder="Enter Username" required></td>
                <td>Password <span style="color:red;">*</span></td><td><input name = "tpassword" id="tpassword" type="password"  placeholder="Enter Password" required></td>
                </tr>
            </table>
            <?php
           if (IsHead()) {
            ?>
            <input style="margin: 0 45%;" type="button" id="submitpros" value="Add Team Member" onclick="addTeamMember();" />
            <?php
                }
            ?>
        </form>
    </div>
</div>
<br/>
<br/>

<div id="myGrid" class="ag-theme-fresh" style="height:500px;width:75%;margin:0 auto;border: 1px solid gray"></div>

<script>
    function addTeamMember(){
        var tname = $("#tname").val();
        var tphone =$("#tphone").val();
        var temail =$("#temail").val();
        var role = $("#role").val();
        var tlogin = $("#tlogin").val();
        var tpassword = $("#tpassword").val();
        var department = $("#purposeId").val();
        var co_roleId = $("#company_role").val();
        var email = $("#temail").val();
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(!pattern.test(email)){         
             alert("Enter valid email id");
             $("#temail").focus();
             return false;
        }
        else if(tname==""){
            alert("Please enter name"); 
              $("#tname").focus();
            return false;
        }else if(tphone==""){
            alert("Please enter contact number");
              $("#tphone").focus();
            return false;
        }else if(department==0){
            alert("Please Select a department");
            return false;
        }
        else if(department!=0 && co_roleId==0){
            alert("Please Select a Company Role");
            return false;
        }
        else if(temail==""){
            alert("Please enter email id");
            return false;
        }
        else if($('input[name=membertype]:checked').length<=0){
         alert("Please select member type");
         return false;
        }
        else if(tlogin==""){
            alert("Please enter loginname");
            return false;
        }else if(tpassword==""){
            alert("Please enter password");
            return false;
        }else{

           var data = $("#teamform").serialize();
           jQuery.ajax({
                    type: "POST",
                    url: "route_team.php",
                    data: "insert_team_member=1&"+data,
                    success: function(data){
                        var result=JSON.parse(data);
                        if(result.isExecutedOut=="1"){
                            alert("Team Member Added Successfully");
                            fetch_team_list();
                            $("#teamform")[0].reset();
                        }
                        else if(result.isExecutedOut=="0"){
                            alert("Username is already taken");
                            $("#tlogin").focus();
                            return false;
                        }
                        else{
                            alert("Team Member is already added.");
                            $("#teamform")[0].reset();
                        }
                    }
            });
        }
    }

    function onlyNos(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else { return true; }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        catch (err) {
            alert(err.Description);
        }
    } 
</script>
<script>
    jQuery(document).ready(function () {  
        $("#company_role").attr('readonly',true);
        jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "get_department=1",
            success: function(data){
                var result=JSON.parse(data);
                $('#departmentId').html("");
                $('#departmentId').append('<option value = '+"0"+'>'+"Select Department"+'</option>');
                $.each(result ,function(i,text){
                    $('#departmentId').append('<option value = '+text.department_id+'>'+text.department+'</option>');
                });
            }
        });

        fetch_team_list();
    });

    $("#departmentId").change(function(){
            var departmentId = $("#departmentId").val();
            if(departmentId!='0'){
                $("#company_role").attr('readonly',false);
                jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    data: "get_company_role=1&departmentId="+departmentId,
                    success: function(data){
                        var data=JSON.parse(data);
                        $('#company_role').html("");
                        $('#company_role').append('<option value = '+"0"+'>'+"Select Role"+'</option>');
                        $.each(data ,function(i,text){
                            $('#company_role').append('<option value = '+text.c_roleId+'>'+text.companyRole+'</option>');
                        });
                    }
                });  
            }
            else{
                 $("#company_role").attr('readonly',true);
            }
    });

    function fetch_team_list(){
        jQuery.ajax({
                type: "POST",
                url: "route_team.php",
                data: "get_team_members=1",
                success: function(data){
                    var result=JSON.parse(data);
                    gridOptions.api.setRowData(result);
                }
        });
    }
</script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
        {headerName:'Name',field: 'name',width:200,filter: 'agTextColumnFilter'},
        {headerName:'Phone',field: 'phone',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Email',field: 'email',width:200,filter: 'agTextColumnFilter'},
        {headerName:'Role',field: 'role',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Department',field: 'department',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Member Type',field: 'member',width:150,filter: 'agTextColumnFilter'}
    ];
    function editCellRenderer(params){
        return "<a href='edit_team.php?sid="+params.data.teamid+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowSelection:'multiple',
        animateRows:true,
        columnDefs: columnDefs,
        components: {editCellRenderer : editCellRenderer
        }
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>