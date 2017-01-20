<div class="col-md-12">
   <div class="row">
        <div class="box" style="margin-left: 14px;">
            <button class="btn btn-primary" ng-click="newPortfolio()"><i class="fa fa-plus"></i> Add</button>

            <form class="form-horizontal" method="POST" ng-submit="addPortfolio()" ng-show="showform" name="addform" enctype="multipart/form-data">
                <h3>New Portfolio</h3>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" ng-model="newportfolio.name" required/>
                    </div>
                    <label for="" class="control-label col-md-1">Date</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" datepicker-popup="dd-MM-yyyy" ng-model="newportfolio.date" close-text="Close" is-open="date.opened" ng-focus="date.opened=true"/>

<!--                        <input type="text" class="form-control" datepicker-popup="dd-MMMM-yyyy" ng-model="fromDate"  is-open="frompicker" show-button-bar="false" show-weeks="false" readonly>-->
<!--                            <span class="input-group-btn">-->
<!--                                <button type="button" class="btn btn-default" ng-click="frompicker=true"><i class="fa fa-calendar"></i></button>-->
<!--                            </span>-->
                    </div>

                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Type</label>
                    <div class="col-md-4">
                        <select name="type" id="type" class="form-control" ng-model="newportfolio.type" >
                            <option value="" selected disabled>Select</option>
                            <option value="portfolio">portfolio site</option>
                            <option value="web app">Web Application</option>
                            <option value="mobile">Mobile App</option>
                            <option value="standalone">Standalone Application</option>
                        </select>
                    </div>

                    <label for="" class="control-label col-md-1">link</label>
                    <div class="col-md-4">
                            <input ng-disabled="newportfolio.type != 'portfolio'" type="text" class="form-control" name="link" ng-model="newportfolio.link" pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" onblur="checkURL(this);"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Client</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="clientname" ng-model="newportfolio.clientname"/>
                    </div>
                    <label for="" class="control-label col-md-1">Description</label>
                    <div class="col-md-4">
                        <textarea name="description" id="description" class="form-control" ng-model="newportfolio.description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Feedback</label>
                    <div class="col-md-9">
                        <textarea name="feedback" id="feedback" class="form-control" ng-model="newportfolio.feedback"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Desktop</label>
                    <div class="col-md-4">
                        <input type="file" id="file1" name="file" multiple file-model="files.desktop" ng-required="!newportfolio.id" />
                    </div>
                    <div class="col-md-5">

                        <span class="alert alert-warning">Image should be 800*548</span>
                    </div>
                    <div class="clearfix"></div>

                <!--for show exist images-->
                    <div class="row">
                        <div class="col-md-2" ng-repeat="(key,preview) in item_files" ng-show="preview.image_type == 'desktop'">
                            <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                                <div class="caption" ng-show="showcaption">
                                    <div id="content">
                                        <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                        <a href="" class="label label-danger" rel="tooltip" title="Delete" confirmed-click="deleteImage(preview)" ng-confirm-click="Would you like to delete this item?!">Delete</a>
                                    </div>
                                </div>
                                <img src="{{public_url + '/uploads/' + preview.file_name}}" alt="thumbnails">
                            </div>
                        </div>
                    </div>

            <!--                for selected images-->
                    <div class="row" >
                        <div class="col-md-2" ng-repeat="image in filespre" ng-show="image.model == 'files.desktop'">
                            <div class="thumbnail">
                                <img src="{{image.url}}" alt="preview" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-md-1">Mobile</label>
                    <div class="col-md-4">
                        <input type="file" id="mobile" name="mobile" multiple file-model="files.mobile"/>
                    </div>
                    <span class="alert alert-warning">Image should be 333*547</span>
                    <div class="clearfix"></div>
                    <!--                for show exist images-->
                    <div class="row">
                        <div class="col-md-2" ng-repeat="(key,preview) in item_files" ng-show="preview.image_type == 'mobile'">
                            <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                                <div class="caption" ng-show="showcaption">
                                    <div id="content">
                                        <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                        <a href="" class="label label-danger" rel="tooltip" title="Delete" confirmed-click="deleteImage(preview)" ng-confirm-click="Would you like to delete this item?!">Delete</a>
                                    </div>
                                </div>
                                <img src="{{public_url + '/uploads/' + preview.file_name}}" alt="thumbnails">
                            </div>
                        </div>
                    </div>

                    <!--                for selected images-->
                    <div class="row" >
                        <div class="col-md-2" ng-repeat="image in filespre" ng-show="image.model == 'files.mobile'">
                            <div class="thumbnail">
                                <img src="{{image.url}}" alt="preview" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container" ng-show="show_error">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-danger alert-dismissable fade in ">
                                <a href="" class="close" data-dismiss="alert" arial-label="close" ng-click="showform=false">&times;</a>
                                <h4>following files are not uploaded</h4>
                                <p ng-repeat-start="err in error">{{err}}</p>
                                <hr ng-repeat-end />
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
                    <label for="">Show</label>
                    <select name="numperPage" ng-model="numPerPage" class="form-control"
                        ng-options="num for num in paginations"{{num}}>
                    </select>

                    <label >Search</label>
                    <input type="text" ng-model="search" class="form-control" placeholder="Search">
                </div>
            </form>
        </div>
   </div>
    <div class="help-block" ng-show="!showtable">{{message}}</div>
    <div class="row">
        <table class="table table-bordered" ng-show="showtable">
            <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Client</th>
                <th>Type</th>
                <th>Date</th>
                <th>Description</th>
                <th>Feedback</th>
                <th>Link</th>
                <th>Photos</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr dir-paginate="portfolio in portfolios | filter:search | limitTo:pageSize| itemsPerPage:numPerPage">
                <td>{{portfolio.id}}</td>
                <td>{{portfolio.name}}</td>
                <td>{{portfolio.clientname}}</td>
                <td>{{portfolio.type}}</td>
                <td>{{portfolio.date}}</td>
                <td><p class="description" popover="{{portfolio.description}}" popover-trigger="mouseenter">{{portfolio.description}}</p></td></td>
                <td><p class="description" popover="{{portfolio.feedback}}" popover-trigger="mouseenter">{{portfolio.feedback}}</p></td>
                <td><p class="description" popover="{{portfolio.link}}" popover-trigger="mouseenter"><a href="{{portfolio.link}}">{{portfolio.link}}</a></p></td>
                <td>
                    <a ng-repeat="file in portfolio.files" href="{{public_url + '/uploads/' + file.file_name}}"><img class="img img-thumbnail" src="{{public_url + '/uploads/' + file.file_name}}" alt="thumbnail" width="25px" height="25px"/></a>
                <td>
                    <div  class="btn-group btn-group-xs" role="group">
                        <button type="button" class="btn btn-info" ng-click="showForm(portfolio)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button  type="button" class="btn btn-danger" confirmed-click="deletePortfolio(portfolio)" ng-confirm-click="Would you like to delete this item?!">
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
</div>
<div class="col-md-12" ng-show="loading">
    <div class="row" >
        <div class="span4">
            <img class="center-block" src="<?php echo base_url() . 'assets/img/loading.gif' ?>" alt=""/>
        </div>
    </div>
</div>
