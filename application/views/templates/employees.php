<div class="col-md-12">
    <div class="row">
       <div class="box" style="margin-left: 14px;">
           <button class="btn btn-primary" ng-click="newEmployee()"><i class="fa fa-plus"></i> Add</button>
           <form class="form-horizontal" method="POST" ng-show="showform" ng-submit="addEmployee()">
               <h3>New Employee</h3>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Name</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" name="name" ng-model="newemployee.name"/>
                   </div>
                   <label for="" class="control-label col-md-1">Designation</label>
                   <div class="col-md-4">
                       <input class="form-control" type="text" ng-model="newemployee.designation" name="designation"/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Gender</label>
                   <div class="col-md-4">
                       <select name="gender" class="form-control">
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
                       <input type="text" class="form-control" ng-model="newemployee.email" name="email"/>
                   </div>
                   <label for="" class="control-label col-md-1">Linkedin</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.linkedin"/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Facebook</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.facebook" name="facebook"/>
                   </div>
                   <label for="" class="control-label col-md-1">Twitter</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.facebook" name="twitter"/>
                   </div>
               </div>
               <div class="form-group">
                   <label for="" class="control-label col-md-1">Google Plus</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.googleplus"/>
                   </div>
                   <label for="" class="control-label col-md-1">GitHub</label>
                   <div class="col-md-4">
                       <input type="text" class="form-control" ng-model="newemployee.github" name="github"/>
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
                       <select name="numPerPage" ng-model="numPerPage" class="form-control">
                           <option value="5">5</option>
                           <option value="10">10</option>
                           <option value="20">20</option>
                           <option value="30">30</option>
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
    <table class="table table-bordered">
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
        <tr dir-paginate="employee in employees | filter:search | limitTo:pageSize | itemsPerPage:numPerpage">
            <td>{{employee.id}}</td>
            <td>{{employee.name}}</td>
            <td>{{employee.designation}}</td>
            <td>{{employee.photo}}</td>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <!--<div class="col-md-3">
        <div class="row">
            <div id="widget">
                <div id="outline">
                    <div class="date">
                        <div id="month"></div>
                        <div id="day"></div>
                        <div id="week"></div>
                    </div>
                    <div class="time">
                        <div id="hour"></div>
                        <div id="minut"></div>
                        <div id="second"></div>
                        <div id="date"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
