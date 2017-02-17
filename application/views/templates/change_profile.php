<div class="col-md-12"  ng-class="{disable : loading} ">
    <div class="row">
        <div class="box" style="margin-left: 14px;">
            <form class="form-horizontal" method="POST" ng-submit="changeProfile()" name="myForm" ng-class="{disabled : formdisable}">
                <h3>Change Username and password</h3>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">New username</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="username" ng-model="newuser.username" placeholder="New Username" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">Current Password</label>
                    <div class="col-md-4">
                        <input type="password" class="form-control" name="curPassword" ng-model="newuser.curpassword" placeholder="Current Password" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">New Password</label>
                    <div class="col-md-4">
                        <input type="password" class="form-control" name="password" ng-model="newuser.password" placeholder="New Password" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-2">Confirm Password</label>
                    <div class="col-md-4">
                        <input type="password" class="form-control" name="confirmPassword" ng-model="newuser.confirmpassword" ng-match="newuser.password" placeholder="Re-Type Password"required=""/>
                    </div>
                    <span ng-show="newuser.password != newuser.confirmpassword">Password do not match</span>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary" ng-disabled="newuser.password != newuser.confirmpassword" type="submit">Save</button>
                    <button class="btn btn-danger" type="button" ng-click="cancel()">Cancel</button>
                </div>
            </form>
            <div class="col-md-12 align-center" ng-show="showerror">
                <div class="alert alert-danger col-md-5" role="alert">
                    <button type="button" class="close" aria-label="Close" ng-click="reset()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Error </strong> You should check username and password.
                </div>
            </div>

            <div class="col-md-12 align-center" ng-show="showmsg">
                <div class="alert alert-success col-md-5" role="alert">
                    <button type="button" class="close" aria-label="Close" ng-click="reset()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Success </strong> Username and password changed.
                </div>
            </div>
        </div>
    </div>
</div>
<div id="loading" ng-show="loading">
    <div id="loading-image">
        <img src="<?php echo public_url() . 'assets/admin/img/loading.gif' ?>" alt=""/>
        <h4>Please wait...</h4>
    </div>

</div>