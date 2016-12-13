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
                <label for="" class="control-label col-md-1">Type</label>
                <div class="col-md-4">
                    <select name="type" id="type" class="form-control" ng-model="newportfolio.type" required>
                        <option value="" selected disabled>Select</option>
                        <option value="portfolio site">portfolio site</option>
                        <option value="web app">Web Application</option>
                        <option value="standalone">Standalone Application</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Description</label>
                <div class="col-md-4">
                    <textarea name="description" id="description" class="form-control" ng-model="newportfolio.description"></textarea>
                </div>
                <label for="" class="control-label col-md-1">link</label>
                <div class="col-md-4">
                        <input type="text" class="form-control" name="link" ng-model="newportfolio.link" pattern="[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?" onblur="checkURL(this);" <!--required-->/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Display Text</label>
                <div class="col-md-9">
                    <textarea name="displaytext" id="displaytext" class="form-control" ng-model="newportfolio.displaytext"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Desktop</label>
                <div class="col-md-3">
                    <input type="file" id="file1" name="file" multiple file-model="files.desktop" ng-required="!newportfolio.id" />
                </div>
                <div class="clearfix"></div>

<!--                for show exist images-->
                <div class="row">
                    <div class="col-md-2" ng-repeat="(key,preview) in item_files" ng-show="preview.image_type == 'desktop'">
                        <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                            <div class="caption" ng-show="showcaption">
                                <div id="content">
                                    <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                    <a href="" class="label label-danger" rel="tooltip" title="Delete" ng-click="deleteImage(preview,key)">Delete</a>
                                </div>
                            </div>
                            <img src="{{base_url + '/uploads/' + preview.file_name}}" alt="thumbnails">
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
                <div class="col-md-3">
                    <input type="file" id="mobile" name="mobile" multiple file-model="files.mobile"/>
                </div>
                <div class="clearfix"></div>
                <!--                for show exist images-->
                <div class="row">
                    <div class="col-md-2" ng-repeat="(key,preview) in item_files" ng-show="preview.image_type == 'mobile'">
                        <div class="thumbnail" ng-mouseover="showcaption=true" ng-mouseleave="showcaption=false">
                            <div class="caption" ng-show="showcaption">
                                <div id="content">
                                    <a href="" class="label label-warning" rel="tooltip" title="Show">Show</a>
                                    <a href="" class="label label-danger" rel="tooltip" title="Delete" ng-click="deleteImage(preview,key)">Delete</a>
                                </div>
                            </div>
                            <img src="{{base_url + '/uploads/' + preview.file_name}}" alt="thumbnails">
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
            <div class="form-group text-center">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-danger" type="button" ng-click="hideForm()">Cancel</button>
            </div>
        </form>
        <form class="form-inline" ng-show="showtable">
            <div class="form-group">
                <label for="">Show</label>
                <select name="numperpage" ng-model="numPerpage" class="form-control">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>

                <label >Search</label>
                <input type="text" ng-model="search" class="form-control" placeholder="Search">
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
            <th>type</th>
            <th>Description</th>
            <th>Display Text</th>
            <th>Link</th>
            <th>Photos</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <tr dir-paginate="portfolio in portfolios | filter:search | limitTo:pageSize| itemsPerPage:numPerpage">
            <td>{{portfolio.id}}</td>
            <td>{{portfolio.name}}</td>
            <td>{{portfolio.type}}</td>
            <td><p class="description" popover="{{portfolio.displaytext}}" popover-trigger="mouseenter">{{portfolio.description}}</p></td></td>
            <td><p class="description" popover="{{portfolio.displaytext}}" popover-trigger="mouseenter">{{portfolio.displaytext}}</p></td>
            <td><p class="description" popover="{{portfolio.link}}" popover-trigger="mouseenter"><a href="{{portfolio.link}}">{{portfolio.link}}</a></p></td>
            <td>
                <a ng-repeat="file in portfolio.files" href="{{base_url + '/uploads/' + file.file_name}}"><img class="img img-thumbnail" src="{{base_url + '/uploads/' + file.file_name}}" alt="thumbnail" width="25px" height="25px"/></a>
            <td>
                <div  class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-info" ng-click="showForm(portfolio)">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button  type="button" class="btn btn-danger" ng-click="deletePortfolio(portfolio)">
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
<div class="row" ng-show="loading">
    <div class="span4">
        <img class="center-block" src="<?php echo base_url('img/loading.gif') ?>" alt=""/>
    </div>
</div>
