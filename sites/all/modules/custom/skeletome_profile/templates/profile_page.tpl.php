<style type="text/css">
    .profile-page-picture {
        width: 100%;
        display: block;
    }


    .edit-picture-button {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 100;;
    }
    .isEditing.edit-picture-button {
        position: inherit;
        float: right;
    }
    .profile-page-picture {

    }
</style>
<?php
    global $user;
    $canEdit = ((arg(1) == $user->uid) || user_access('administer site configuration'));;
    $isRegistered = isset($user->uid) && $user->uid != 0;
?>
<div ng-controller="ProfileCtrl" ng-init="init()">


    <div class="row-fluid">
        <div class="span12">
            <div class="page-heading">
                <div class="breadcrumbs">
                    <span><a href="{{ baseUrl }}">Home</a> &#187; </span>
                </div>
                <h1>
                    <span class="type-logo"><i class="ficon-user"></i></span>
                    {{ user.name | capitalize }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Import from Orcid -->
    <div class="row-fluid">
        <div class="span3">
            <section>

                <div class="section-segment" ng-class="{ 'section-segment-editing': detailsState=='isEditing', 'section-segment-nopadding': detailsState=='isDisplaying' }" style="position: relative;">

                    <?php if($canEdit): ?>
                    <div ng-class="{'media-body': detailsState=='isEditing'}">
                        <div class="edit-picture-button" ng-class="{'isEditing': detailsState=='isEditing'}">
                            <div ng-switch on="detailsState">
                                <div ng-switch-when="isLoading">
                                </div>
                                <div ng-switch-when="isEditing">
                                    <save-button click="saveDetails(edit.profile)"></save-button>
                                    <cancel-button click="cancelDetails()"></cancel-button>
                                </div>
                                <div ng-switch-when="isDisplaying">
                                    <edit-button click="editDetails()"></edit-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div ng-switch on="detailsState">
                        <div ng-switch-when="isLoading">
                            <refresh-box></refresh-box>
                        </div>
                        <div ng-switch-when="isEditing">
                            <div class="dropzone" ng-model="profilePics.files"
                                 drop-zone-upload="?q=upload/images" drop-zone-message="<b>Drop Profile picture</b> in here to upload (or click here).">
                            </div>
                            <img ng-show="!edit.profile.field_profile_image.und.length" style="opacity: 0.5" src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/user_black.svg" class="profile-page-picture"/>
                            <img ng-show="edit.profile.field_profile_image.und.length"  ng-src="{{ edit.profile.field_profile_image.und[0].full_url }}" alt=""  class="profile-page-picture"/>
                        </div>
                        <div ng-switch-when="isDisplaying">
                            <div ng-show="!profile.field_profile_image.und.length" style="padding: 20px">
                                <img class="profile-page-picture" style="opacity: 0.5" src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/user_black.svg"/>
                            </div>

                            <img class="profile-page-picture" ng-show="profile.field_profile_image.und.length"  ng-src="{{ profile.field_profile_image.und[0].full_url }}" alt=""/>
                        </div>
                    </div>

                    <!--<img src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/templates/andreaszankl.jpg"/>-->
                </div>
                <!--<div class="section-segment sectioh-segment-header">
                    <h3>Details</h3>
                </div>-->

                <div ng-repeat="role in roles" class="section-segment">
                    <b>Skeletome</b> {{ role }}
                </div>

                <?php if($isRegistered): ?>
                <div class="section-segment">
                    <b>Email</b> {{ user.mail }}
                </div>
                <?php endif; ?>

                <div class="section-segment">
                    <b>Member Since</b> {{ user.created*1000 | date:'MMM d, y'}}
                </div>

            </section>

            <section ng-show="contributed.length">
                <div class="section-segment section-segment-header">
                    <h3>Contributed to Pages</h3>
                </div>

                <div ng-repeat="page in contributed | limitTo:model.contributedDisplayLimit">
                    <a href="?q=node/{{ page.nid }}" class="section-segment">
                        <i class="ficon-angle-right pull-right"></i>

                        {{ page.title }}
                    </a>
                </div>
                <cm-reveal model="contributed" showing-count="model.contributedDisplayLimit" default-count="8"></cm-reveal>
            </section>

        </div>

        <div class="span9">
            <section>
                <div class="section-segment section-segment-header" ng-class="{ 'section-segment-editing': biographyState=='isEditing' }">
                    <?php if($canEdit): ?>
                    <div class="section-segment-header-buttons">
                        <div ng-switch on="biographyState">
                            <div ng-switch-when="isLoading">
                            </div>
                            <div ng-switch-when="isEditing">

                                <save-button click="saveBiography(edit.profile)"></save-button>
                                <cancel-button click="cancelBiography()"></cancel-button>

                                <div class="header-divider"></div>

                                <div class="btn-group">
                                    <a class="btn btn-cancel dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="ficon-download-alt"></i> Import from
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-import">
                                        <li><a ng-click="importLinkedInBiography()" ng-show="linkedIn.isAuthenticated" tabindex="-1" href=""><img src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/linkedin.png"/></a></li>
                                        <li><a ng-show="!linkedIn.isAuthenticated" ng-click="linkedIn.disabled = true" ng-disabled="{{ linkedIn.disabled }}" tabindex="-1" href="{{ linkedIn.disabled && '' || linkedIn.authUrl}}"> <img src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/linkedin.png"/></a></li>
                                        <li><a ng-click="importOrcidBiography()" tabindex="-1" href=""> <img src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/orcid-logo.png"/></a></li>
                                    </ul>
                                </div>

                            </div>
                            <div ng-switch-when="isDisplaying">
                                <edit-button click="editBiography()"></edit-button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <h2>Biography</h2>
                </div>

                <div ng-switch on="biographyState">

                    <div ng-switch-when="isLoading">
                        <refresh-box></refresh-box>

                    </div>
                    <div ng-switch-when="isEditing">
                        <div class="section-segment section-segment-nopadding">
                            <textarea ck-editor height="800px" ng-model="edit.profile.body.und[0].value"></textarea>
                        </div>
                    </div>
                    <div ng-switch-when="isDisplaying">
                        <div class="section-segment">

                            <div ng-show="profile.body.und[0].safe_value.length" ng-bind-html-unsafe="profile.body.und[0].safe_value">
                            </div>
                            <div ng-show="!profile.body.und[0].safe_value.length" class="muted">
                                This user has no biography.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>

                <div class="section-segment section-segment-header" ng-class="{ 'section-segment-editing': publicationsState =='isEditing' }">
                    <?php if($canEdit): ?>
                        <div class="section-segment-header-buttons">
                            <div class="pull-right">
                                <div ng-switch on="publicationsState">
                                    <div ng-switch-when="isLoading">
                                    </div>
                                    <div ng-switch-when="isEditing">

                                        <save-button click="savePublications(edit.profile)"></save-button>
                                        <cancel-button click="cancelPublications()"></cancel-button>

                                        <div class="header-divider"></div>

                                        <a ng-click="showAddPublication()" href role="button" class="btn btn-cancel">
                                            <i class="ficon-plus"></i> Add Text Reference
                                        </a>

                                        <div class="btn-group">
                                            <a class="btn btn-cancel dropdown-toggle" data-toggle="dropdown" href="#">
                                                <i class="ficon-download-alt"></i> Import from
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-import">
                                                <li><a ng-click="importOrcidPublications()" tabindex="-1" href=""> <img src="<?php echo base_path() . drupal_get_path('module', 'skeletome_profile'); ?>/img/orcid-logo.png"/></a></li>
                                            </ul>
                                        </div>

                                    </div>
                                    <div ng-switch-when="isDisplaying">
                                        <edit-button click="editPublications()"></edit-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <h2>Publications</h2>
                </div>

                <div ng-switch on="publicationsState">
                    <div ng-switch-when="isLoading">
                        <refresh-box></refresh-box>
                    </div>
                    <div ng-switch-when="isEditing">

                        <remove-list list-model="edit.profile.field_profile_publications.und"></remove-list>

                    </div>
                    <div ng-switch-when="isDisplaying">
                        <div class="section-segment media-body" ng-show="!profile.field_profile_publications.und.length">
                            <div class="muted">
                                No publications.
                            </div>
                        </div>
                        <div ng-repeat="publication in profile.field_profile_publications.und | limitTo:model.publicationDisplayLimit" class="section-segment" ng-bind-html-unsafe="publication.value">
                        </div>

                        <cm-reveal model="profile.field_profile_publications.und" showing-count="model.publicationDisplayLimit" default-count="3"></cm-reveal>
                    </div>
                </div>
            </section>

            <section>
                <div class="section-segment section-segment-header">
                    <h3>Approved Statements ({{ approvedStatements.length }})</h3>
                </div>

                <div ng-repeat="statement in approvedStatements | limitTo:model.approvedStatementsDisplayLimit" >

                    <a class="section-segment" href="?q=node/{{ statement.field_statement_node.und[0].target_id }}#{{ statement.nid }}" >
                        <i class="ficon-angle-right pull-right"></i>
                        <i class="ficon-ok pull-left" style="position: relative; top: 6px; margin-right: 10px"></i>
                        <span ng-bind-html-unsafe="statement.body.und[0].value"></span>
                    </a>
                </div>
                <cm-reveal model="approvedStatements" showing-count="model.approvedStatementsDisplayLimit" default-count="3"></cm-reveal>
            </section>

            <section>
                <div class="section-segment section-segment-header">
                    <h3>Recent Activity</h3>
                </div>
                <div ng-show="!activity.length" class="section-segment muted">
                    No recent activity.
                </div>
                <div ng-repeat="item in activity">
                    <a ng-show="!item.cid" class="section-segment" href="?q=node/{{ item.target_nid }}#{{ item.nid }}">
                        <i class="ficon-angle-right pull-right"></i>

                        <p><i class="icon-statement"></i> <b>Statement</b> added to <b>{{ item.target_title }}</b> <span class="muted" style="margin-left: 7px">{{ item.created*1000 | date:'MMM d, y' }}</span></p>

                        <div>
                            <span>"</span><span ng-bind-html-unsafe="item.body | truncate:200"></span><span>"</span>
                        </div>

                    </a>

                    <a ng-show="item.cid" class="section-segment" href="?q=node/{{ item.target_nid }}">
                        <i class="ficon-angle-right pull-right"></i>

                        <p><i class="ficon-comment"></i> <b>Comment</b> added to a statement on <b>{{ item.target_title }}</b> <span class="muted" style="margin-left: 7px">{{ item.created*1000 | date:'MMM d, y' }}</span></p>

                        <div>
                            <span>"</span><span ng-bind-html-unsafe="item.body | truncate:200"></span><span>"</span>
                        </div>
                    </a>
                </div>

            </section>
        </div>
    </div>

    <my-modal visible="isAddingPublication">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Add Reference</h3>
        </div>
        <textarea ng-model="edit.newProfileText" ck-editor></textarea>

        <div class="modal-footer">
            <a ng-click="addPublication(edit.newProfileText)" ng-disabled="!edit.newProfileText.length" class="btn btn-save" href=""><i class="ficon-plus"></i> Add</a>
        </div>
    </my-modal>

    <my-modal visible="isShowingImportFromOrcid">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Import from Orcid</h3>
        </div>

        <div class="section-segment">
            <p>
                Enter your Orcid ID. Only public information on Orcid can be imported.
            </p>
            <div>
                Orcid ID <input type="text" ng-model="orcidId" placeholder="0000-0000-0000-0000"/> <i class="ficon-question-sign" cm-tooltip cm-tooltip-content="Your Orcid ID can be found on your Orcid profile page."></i>
            </div>
        </div>
        <div class="section-segment media-body">
            <a class="btn btn-save" href="" ng-enabled="orcidId.length >= 16" ng-click="importFromOrcid(orcidId)">Import</a>
            <a class="btn btn-cancel" href="" ng-click="hideImportFromOrcid()">Cancel</a>
        </div>
    </my-modal>
</div>

