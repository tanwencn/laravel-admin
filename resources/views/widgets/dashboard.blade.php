<div class="row">
    <section class="col-lg-6 connectedSortable">
        <div class="col-lg-6 col-lg-12" style="padding-left: 0">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $posts_count }}</h3>

                    <p>{{ trans_choice('admin.post', 1) }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('admin.posts.index') }}" class="small-box-footer">{{ trans('admin.more') }} <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-lg-12" style="padding-right: 0">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $pages_count }}</h3>

                    <p>{{ trans_choice('admin.page', 1) }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin.pages.index') }}" class="small-box-footer">{{ trans('admin.more') }} <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </section>

    <section class="col-lg-6 connectedSortable">
        <div class="col-lg-6 col-xs-12" style="padding-left: 0;">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $users_count }}</h3>

                    <p>{{ trans_choice('admin.user', 1) }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">{{ trans('admin.more') }} <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-12" style="padding-right: 0">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $comments_count }}</h3>

                    <p>{{ trans_choice('admin.comment', 1) }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="small-box-footer">{{ trans('admin.more') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </section>
</div>