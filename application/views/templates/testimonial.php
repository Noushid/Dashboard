<div class="col-md-12">
    <div class="row">
        <div class="box" style="margin-left: 14px;">
            <button class="btn btn-primary" ng-click="newTestimonial()"><i class="fa fa-plus"></i> Add</button>
            <form class="form-horizontal" method="POST" ng-show="showform" ng-submit="addTestimonial()">
                <h3>New Testimonial</h3>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" ng-model="newtestimonial.name" required=""/>
                    </div>
                    <label for="" class="control-label col-md-1">Link</label>
                    <div class="col-md-4">
                        <input class="form-control" type="text" ng-model="newtestimonial.link" name="link" pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" onblur="checkURL(this);"  />
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Description</label>
                    <div class="col-md-9">
                        <textarea class="form-control" ng-model="newtestimonial.description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Photo</label>
                    <div class="col-md-4">
                        <input type="file" name="photo" id="photo" file-model="files.photo" />
                    </div>
                    <span class="alert alert-warning">Image should be 50*50</span>

                    <!--                   for existing image-->
                    <div class="clearfix"></div>
                    <div class="row" ng-show="newtestimonial.file_name" style="margin-left: 14px">
                        <div class="col-md-2">
                            <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                                <div class="caption" ng-show="showcaption">
                                    <div id="content">
                                        <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                        <a href="" class="label label-danger" rel="tooltip" title="Delete" ng-click="deleteImage(newtestimonial)">Delete</a>
                                    </div>
                                </div>
                                <img src="{{public_url + '/uploads/' + newtestimonial.file_name}}" alt="thumbnails">
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
            <form class="form-inline" ng-show="showtable">
                <div class="form-group">
                    <label for="" class="control-label col-md-2">Show</label>
                    <div class="col-md-3">
                        <select name="numPerPage" ng-model="numPerPage" class="form-control"
                            ng-options="num for num in paginations">{{num}}
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
            <th>id</th>
            <th>name</th>
            <th>link</th>
            <th>Description</th>
            <th>photo</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <tr dir-paginate="testimonial in testimonials | filter:search | limitTo:pageSize | itemsPerPage:numPerPage">
            <td>{{testimonial.id}}</td>
            <td>{{testimonial.name}}</td>
            <td><p class="description" popover="{{testimonial.link}}" popover-trigger="mouseenter"><a href="{{testimonial.link}}">{{testimonial.link}}</a></p></td>
            <td><p class="description" popover="{{testimonial.description}}" popover-trigger="mouseenter">{{testimonial.description}}</p></td></td>
            <td>
                <a href="{{public_url + '/uploads/' + testimonial.file_name}}"><img class="img img-thumbnail" src="{{public_url + '/uploads/' + testimonial.file_name}}" alt="thumbnail" width="25px" height="25px"/></a>
            </td>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info" ng-click="showForm(testimonial)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger" confirmed-click="deleteTestimonial(testimonial)" ng-confirm-click="Would you like to delete this item?!">
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

