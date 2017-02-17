<div class="col-md-12">
    <div class="row">
       <div class="box" style="margin-left: 14px;">
           <button class="btn btn-primary" ng-click="newEmployee()"><i class="fa fa-plus"></i> Add</button>
           <form class="form-horizontal" method="POST" ng-show="showform" ng-submit="addEmployee()">
               <h3>New Employee</h3>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Name</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" name="name" ng-model="newemployee.name" required=""/>
                   </div>
                   <label for="" class="control-label col-md-1">Designation</label>
                   <div class="col-md-4">
                       <input class="form-control" type="text" ng-model="newemployee.designation" name="designation" required=""/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Gender</label>
                   <div class="col-md-4">
                       <select name="gender" class="form-control" ng-model="newemployee.gender" required="">
                           <option value="" selected disabled>select</option>
                           <option value="male">Male</option>
                           <option value="female">Female</option>
                       </select>
                   </div>

                   <label for="" class="control-label col-md-1">Address</label>
                   <div class="col-md-4">
                       <textarea class="form-control" name="address" ng-model="newemployee.address"></textarea>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">place</label>
                   <div class="col-md-4">
                       <input class="form-control" type="text" ng-model="newemployee.place" name="place"/>
                   </div>
                   <label for="" class="control-label col-md-1">Mobile</label>
                   <div class="col-md-4">
                       <input class="form-control" type="text" ng-model="newemployee.mobile" name="mobile"/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Email</label>
                   <div class="col-md-4">
                       <input type="email" class="form-control" ng-model="newemployee.email" name="email"/>
                   </div>
                   <label for="" class="control-label col-md-1">Linkedin</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.linkedin" pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" />
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Facebook</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.facebook" name="facebook"  pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" />
                   </div>
                   <label for="" class="control-label col-md-1">Twitter</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.twitter" name="twitter"  pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" />
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Google Plus</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.googleplus"  pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" />
                   </div>
                   <label for="" class="control-label col-md-1">GitHub</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.github" name="github"  pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" />
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Photo</label>
                   <div class="col-md-4">
                       <input type="file" name="photo" id="photo" file-model="files.photo" ng-required="!newemployee.id"/>
                   </div>
                   <span class="alert alert-warning">Image should be 300*300</span>

<!--                   for existing image-->
                   <div class="clearfix"></div>
                   <div class="row" ng-show="newemployee.file_name" style="margin-left: 14px">
                       <div class="col-md-2">
                           <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                               <div class="caption" ng-show="showcaption">
                                   <div id="content">
                                       <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                   </div>
                               </div>
                               <img src="{{public_url + '/uploads/' + newemployee.file_name}}" alt="thumbnails">
                           </div>
                       </div>
                   </div>

<!--                   for selected -->
                   <div class="clearfix"></div>
                   <div class="row" style="margin-left: 14px">
                       <div class="col-md-2" ng-repeat="image in filespre">
                           <div class="thumbnail">
                               <img src="{{image.url}}" alt="preview"/>
                           </div>
                       </div>

                   </div>
               </div>
               <div class="form-group text-center">
                   <button class="btn btn-primary" type="submit">Save</button>
                   <button class="btn btn-danger" type="button" ng-click="hideForm()">Cancel</button>
               </div>
           </form>
           <form class="form-inline">
               <div class="form-group">
                   <label for="" class="control-label col-md-2">Show</label>
                   <div class="col-md-3">
                       <select name="numPerPage" ng-model="numPerPage" class="form-control"
                           ng-options="num for num in paginations" {{num}}>
                       </select>
                   </div>

                   <label class="control-label col-md-2">Search</label>
                   <div class="col-md-3">
                       <input type="text" ng-model="search" class="form-control" placeholder="Search">
                   </div>
               </div>
           </form>
       </div>
    </div>
    <div class="help-block" ng-show="!showtable">{{message}}</div>
    <table class="table table-bordered" ng-show="showtable">
        <thead>
        <tr>
            <td>id</td>
            <td>name</td>
            <td>Designation</td>
            <td>photo</td>
            <td>action</td>
        </tr>
        </thead>
        <tbody>
        <tr dir-paginate="employee in employees | filter:search | limitTo:pageSize | itemsPerPage:numPerPage">
            <td>{{employee.id}}</td>
            <td>{{employee.name}}</td>
            <td>{{employee.designation}}</td>
            <td>
                <a href="{{public_url + '/uploads/' + employee.file_name}}"><img class="img img-thumbnail" src="{{public_url + '/uploads/' + employee.file_name}}" alt="thumbnail" width="25px" height="25px"/></a>
            </td>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info" ng-click="showForm(employee)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger" confirmed-click="deleteEmployee(employee)" ng-confirm-click="Would you like to delete this item?!">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <dir-pagination-controls
        max-size="10"
        direction-links="true"
        boundary-links="true" >
    </dir-pagination-controls>
</div>
<div id="loading" ng-show="loading">
    <div id="loading-image">
        <img src="<?php echo public_url() . 'assets/admin/img/loading.gif' ?>" alt=""/>
        <h4>Please wait...</h4>
    </div>
</div>