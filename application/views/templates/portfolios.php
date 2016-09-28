<div class="col-md-12">
   <div class="row">
    <div class="box" style="margin-left: 14px;">
        <button class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
        <form class="form-horizontal" method="POST" ng-submit="addPortfolio()">
            <h3>New Portfolio</h3>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Name</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="name" ng-model="newportfolio.name"/>
                </div>
                <label for="" class="control-label col-md-1">Type</label>
                <div class="col-md-4">
                    <select name="type" id="type" class="form-control" ng-model="newportfolio.type">
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
                    <input type="text" class="form-control" name="link" ng-model="newportfoliolink"/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-md-1">Display Text</label>
                <div class="col-md-9">
                    <textarea name="displaytText" id="displaytText" class="form-control" ng-model="newportfolio.displaytext"></textarea>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary">Save</button>
                <button class="btn btn-danger">Cancel</button>
            </div>
        </form>
    </div>
   </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td>id</td>
            <td>name</td>
            <td>type</td>
            <td>Description</td>
            <td>Display Text</td>
            <td>Link</td>
            <td>action1</td>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="portfolio in portfolios">
            <td>{{portfolio.id}}</td>
            <td>{{portfolio.name}}</td>
            <td>{{portfolio.type}}</td>
            <td>{{portfolio.description}}</td>
            <td>{{portfolio.displaytext}}</td>
            <td>{{portfolio.link}}</td>
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
</div>
