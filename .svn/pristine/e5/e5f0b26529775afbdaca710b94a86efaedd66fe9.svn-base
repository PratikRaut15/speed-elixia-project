function freshchat(extid,realname,customercompany,email1,phone,restid) {

window.fcSettings = {

    token: "433156cd-00e3-4c99-b0bc-203375bc6926",
    host: "https://wchat.freshchat.com",

    externalId : extid,
    restoreId :  restid,      // user’s id unique to your system
    onInit: function() {
      /*
        window.fcWidget.on("widget:opened", function(resp) {    //This is to move ticket widget when freschat widget if opened
          console.log('Widget Opened');
          var sdLeft = $('#supportDiv').offset().left;
          var spLeft = $('#supportPanel').offset().left;

          $("#supportDiv").css({left:sdLeft})
             .animate({"left":"750px"}, "slow");

                $("#supportPanel").css({left:spLeft})
             .animate({"left":"550px"}, "slow");
          });

      window.fcWidget.on("widget:closed", function(resp) {      //This is to move ticket widget when freschat widget if closed
          console.log('Widget Opened');
        var sdLeft = $('#supportDiv').offset().left;
          var spLeft = $('#supportPanel').offset().left;

          $("#supportDiv").css({left:sdLeft})
             .animate({"left":"1163px"}, "slow");

                $("#supportPanel").css({left:spLeft})
             .animate({"left":"910px"}, "slow");
          });
        */
      window.fcWidget.on('widget:loaded', function() {



        window.fcWidget.user.get().then(function(resp) {

            var status = resp && resp.status,
                data = resp && resp.data;
      var chat_extid = extid;
              if (status === 200) {
                if (data.restoreId) {// Updating restore id in database.
                    chat_restid=data.restoreId;

                     $.ajax({
                            type: "POST",
                            url: "../user/route.php",
                            data: {chat_extid,chat_restid},
                            success: function(data) {


                             },
                            failure: function(data) {


                             }

                        });
                }
              }
        }, function(error) {

       // User Not Created, creating a new user.
          window.fcWidget.user.setProperties({

            firstName: realname,
            lastName: customercompany,
            email:email1,
            phone:phone,
          });

          window.fcWidget.on('user:created', function(resp) {

            var status = resp && resp.status,
                data = resp && resp.data;
            if (status === 200) {
              if (data.restoreId) {
                var chat_extid = extid;
                var chat_restid=data.restoreId;
                $.ajax({
                        type: "POST",
                        url: "../user/route.php",
                        data: {chat_extid,chat_restid},
                        success: function(data) {

                         },
                        failure: function(data) {


                         }

                    });                         // Update restoreId in your database
              }
            }
          });
        });
      });
    }
  };

}
