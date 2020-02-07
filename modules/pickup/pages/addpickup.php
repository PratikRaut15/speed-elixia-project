<form method="POST" id="adduser" name="adduser" enctype="multipart/form-data" class="form-horizontal well " style="width:70%;" >
  <?php include 'panels/addpickup.php'; ?>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Name <span class="mandatory">*</span></span>
        <input type="text" name="pickupname" id="pickupname" placeholder="Name" autofocus>
      </div>
      <div class="input-prepend ">
        <span class="add-on">Email <span class="mandatory">*</span></span>
        <input type="text" name="email" id="email" placeholder="Émail ID " >
      </div>
    </div>
  </fieldset>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Username <span class="mandatory">*</span></span>
        <input type="text" name="username" id="username" placeholder="UserName" >
      </div>
      <div class="input-prepend ">
        <span class="add-on">Password <span class="mandatory">*</span></span>
        <input type="password" name="password" id="password" placeholder="Password " >
      </div>
    </div>
  </fieldset>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span>
        <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" />
      </div>
      <div class="input-prepend ">
        <span class="add-on">Pincode <span class="mandatory">*</span></span>
        <textarea id="pins" name="pins"></textarea>
      </div>
    </div>
  </fieldset>
  <fieldlist>
    <div class="control-group">
      <div class="input-prepend">
        <span>Upload Photo</span> <input type="file" name="pickupboyphoto" id="pickupboyphoto">
        <input id="base64img" name="base64img" type="hidden"/> 
      </div>
    </div>
  </fieldlist>
  <fieldset>
    <div class="control-group pull-right">
      <input type="submit" value="Add User" name='create_user' class="btn btn-primary" onclick="addPickup();
          return false;" >
    </div>
  </fieldset>
</form>