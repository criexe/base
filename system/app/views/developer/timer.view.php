<div class="timer">

    <section class="section">
        <div class="row">

            <?php if( ! timer::active()): ?>
            <div class="col l12">
                <section>
                    <div class="card grey darken-3 grey-text text-lighten-4 light">
                        <div class="card-content">
                            <div class="card-title light orange-text">Command</div>
                            <div class="section">
                                <code>cd <?= ROOT_PATH ?></code>
                            </div>
                            <div class="divider grey darken-1"></div>
                            <div class="section">
                                <code><?= timer::cmd_string() ?></code>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php endif; ?>

            <div class="col l4">

                <section>
                    <?php if( ! timer::active()): ?>
                        <div class="card red darken-3 grey-text text-lighten-5 light">
                            <div class="card-content">
                                <h5 class="thin">The timer can be stopped.</h5>
                            </div>
                            <div class="divider red darken-1"></div>
                            <div class="card-content red darken-4">
                                <?= sys::date($last_runtime) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card green darken-2 grey-text text-lighten-5 light">
                            <div class="card-content">
                                <h5 class="thin">The timer is running successfully.</h5>
                            </div>
                            <div class="divider green darken-1"></div>
                            <div class="card-content green darken-3">
                                <?= sys::date($last_runtime) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <section>
                    <div class="card grey darken-3">
                        <div class="card-content">
                            <div class="card-title light orange-text" style="margin-bottom:10px">Files</div>
                            <?php foreach($timer_files as $file) echo "<span class='chip yellow darken-2'>$file</span>"; ?>
                        </div>
                    </div>
                </section>

            </div>
            <div class="col l8">

                <section>
                    <div class="card grey darken-3 grey-text text-lighten-4 light timer-logs">
                        <div class="card-content">
                            <div class="card-title light orange-text">Counter Data</div>
                            <pre><?= $counter_data ?></pre>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card grey darken-3 grey-text text-lighten-4 light timer-logs">
                        <div class="card-content">
                            <div class="card-title light orange-text">Logs</div>
                            <pre><?= timer::log() ?></pre>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </section>

</div>