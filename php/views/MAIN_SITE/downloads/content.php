<?php
global $CONFIG;
?>
<div class="container">
    <div class="container bg-white">
        <div class="row offset-header">
            <div class="col-sm-6 col-sm-offset-3 text-center margin-top-5">
                <?php $App->printMessage(); ?>
            </div>
        </div>
        <div class="row">
            <h2 class="title text-center animated fadeInUp delayp1" style="opacity: 0;">Where can I get it?</h2>
            <div class="item col-md-12 col-sm-12">
                <div class="animated fadeInUp delayp3 text-center" style="opacity: 0;">
                    <p>PM4W is still a research project and is still limited to a few users. However the project is open source and is provided without any warranty or user support. The project is Licenced under <strong>MIT Licence</strong> and 
                        it's source can be downloaded from <a href="http://github.com/sammyukavi/pm4w" target="_blank">github</a>. The project might contain one or two bugs, some features might not be developed fully and others are missing so pulls and merges are welcome. You can download an unsigned build from here.</p>
                </div>
            </div>
        </div>
        <?php
        $App->con->where('published', 1);
        $App->con->where('preferred', 1);
        $App->con->join('files', 'id_file=file_id', 'LEFT');
        $build = $App->con->getOne('app_builds');
        if (!empty($build)) {
            ?>
            <div class="row margin-top-20 animated fadeInUp delayp4 ">
                <div class="col-md-12 text-center">
                    <strong>Download <?php echo $build['build_name']; ?></strong>                                       
                </div>
            </div>
            <div class="row margin-top-20 animated fadeInUp delayp4 ">
                <div class="col-md-12">
                    <?php echo html_entity_decode($build['build_features']); ?>                                       
                </div>
            </div>
            <div class="row margin-top-20 animated fadeInUp delayp4 ">
                <div class="col-md-12 text-center">
                    <a href="/attachment/<?php echo $build['file_name']; ?>" rel="noindex, nofollow" class="btn btn-lg btn-success">Download APK</a>
                    <span class="help-block text-highlight">
                        <strong>v</strong> <?php echo $build['build_version']; ?>, <?php echo $build['is_stable'] == 1 ? 'Stable' : 'Nightly build'; ?><?php echo empty($build['compatible_devices']) ? '' : ', Compatible Devices: ' . $build['compatible_devices']; ?>                 
                    </span>                    
                </div>
            </div>
        <?php } ?>
        <div class="row margin-top-20 animated fadeInUp delayp5 ">
            <div class="col-md-12 text-center">  
                <h4>Older unsupported builds</h4>
            </div>
        </div>
        <div class="row margin-top-20 animated fadeInUp delayp5 ">
            <div class="col-md-12">       
                <?php
                $App->con->where('published', 1);
                $App->con->where('preferred', 0);
                $App->con->orderBy('build_date', 'DESC');
                $App->con->join('files', 'id_file=file_id', 'LEFT');
                $builds = $App->con->get('app_builds');
                if (count($builds) == 0) {
                    ?>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">   
                            <div class="alert alert-info text-capitalize">
                                Older builds are not available at the moment. Please come back later.
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    //var_dump($builds);
                    ?>
                    <div class="panel-group" id="accordion">
                        <?php
                        foreach ($builds as $build) {
                            //var_dump($build);
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $build['id_build']; ?>">
                                            <?php echo $build['build_name']; ?>
                                            <i class="indicator glyphicon glyphicon-chevron-down  pull-right" style="margin-left: 20px;"></i>
                                            <span class="badge badge-success pull-right"> <?php echo $build['is_stable'] == 1 ? 'Stable' : 'Nightly build'; ?></span>
                                        </a>                                        
                                    </h4>
                                </div>
                                <div id="collapse_<?php echo $build['id_build']; ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php echo html_entity_decode($build['build_features']); ?>
                                    </div>
                                    <div class="panel-footer text-center">
                                        <a href="/attachment/<?php echo $build['file_name']; ?>" rel="noindex, nofollow" class="btn btn-primary">Download <?php echo $build['build_name'] . '-' . $build['build_version'] . '-' . ($build['is_stable'] == 1 ? 'stable' : 'nightly') . '.apk'; ?></a>
                                        <span class="help-block text-highlight" style="display: block; font-style: italic;">
                                            <strong>v</strong> <?php echo $build['build_version']; ?>, <?php echo $build['is_stable'] == 1 ? 'Stable' : 'Nightly build'; ?><?php echo empty($build['compatible_devices']) ? '' : ', Compatible Devices: ' . $build['compatible_devices']; ?>                 
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

