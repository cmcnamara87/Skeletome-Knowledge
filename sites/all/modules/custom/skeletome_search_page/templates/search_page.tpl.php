<style type="text/css">
    em {
        background-color: yellow;
    }
</style>
<div ng-controller="SearchCtrl" ng-init="init()">

    <div class="row-fluid">
        <div class="span12">
            <div class="page-heading">
                <h1>Search Results</h1>
            </div>

        </div>
    </div>
    <div class="row-fluid">
        <div class="span8">
            <section ng-show="!model.results.length && !model.isLoading">
                <div class="section-segment">
                    No results.
                </div>
            </section>
            <section ng-repeat="result in model.results">

                <a class="section-segment section-segment-header" href="?q=node/{{ result.node.entity_id }}">
                    <div class="section-segment-header-buttons pull-right">
                        <i class="ficon-angle-right"></i>
                    </div>

                    <h3 ng-bind-html-unsafe="result.title"></h3>
                </a>
                <div class="section-segment">
                    <b>Abstract </b>
                    <span ng-bind-html-unsafe="result.snippets.content[0]"></span>
                </div>
                <div class="section-segment">
                    <b>Clinical Features</b>

                    <span ng-repeat="clinicalFeature in model.clinicalFeatures"><em>{{ clinicalFeature.name }}, </em></span>
                    <span ng-repeat="clinicalFeature in result.clinical_features">{{ clinicalFeature.name }}, </span>...
                </div>
            </section>

            <section ng-show="model.isLoading">
                <div ng-show="model.isLoading">
                    <refresh-box></refresh-box>
                </div>

            </section>
            <section ng-show="model.moreResults && !model.isLoading">
                <div class="section-segment">
                    <a class="btn btn-reveal" href="" ng-click="loadMore()">Show More</a>
                </div>
            </section>
        </div>
        <div class="span4">

            <section>
                <div class="section-segment section-segment-header">
                    <h3>Filter Clinical Features</h3>
                </div>

                <div class="section-segment" ng-show="!model.results.length && !model.isLoading">
                    No filters.
                </div>

                <div ng-show="model.isLoading">
                    <refresh-box></refresh-box>
                </div>

                <div ng-show="!model.isLoading && model.results.length > 0" ng-repeat="facet in model.facets | limitTo:20">
                    <a class="section-segment" href="" ng-click="addClinicalFeature(facet)">
                        <span class="label pull-right">{{ facet.count }}</span>

                        <span class="btn btn-add"><i class="ficon-plus"></i></span> {{ facet.name }}
                    </a>
                </div>
                <div ng-show="model.results.length > 0" class="section-segment">
                    <a class="btn btn-reveal" href="" ng-click="loadMore()">Show More</a>
                </div>
            </section>
        </div>
    </div>
</div>