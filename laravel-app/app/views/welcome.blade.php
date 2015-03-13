@section('title', 'Welcome')
@section('content')
<div class="row text-center">
        <?php
        if (Entrust::can('lucky')) {
            ?>
            <div class="row content-icon">
                <?php
                //if (Entrust::can('print_ticket'))
                echo '<a href="lucky/print-ticket">
                        <div class="col-md-3 col-sm-3 icon">
                            <span class="glyphicon glyphicon-print"></span>
                            <h2>Print Ticket</h2>
                        </div>
                    </a>
                ';
                ?>
                <?php
                    //if (Entrust::can('print_ticket'))
                    echo '<a href="lucky/cancel-ticket">
                        <div class="col-md-3 col-sm-3 icon">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            <h2>Cancel Ticket</h2>
                        </div>
                    </a>
                ';
                ?>
                <?php
                    //if (Entrust::can('print_ticket'))
                    echo '<a href="lucky/payout">
                        <div class="col-md-3 col-sm-3 icon">
                            <span class="glyphicon glyphicon-usd"></span>
                            <h2>Payout</h2>
                        </div>
                    </a>
                    ';
                ?>
                <?php
                //if (Entrust::can('print_ticket'))
                echo '<a href="lucky/result">
                    <div class="col-md-3 col-sm-3 icon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <h2>Result</h2>
                    </div>
                </a>
                ';
                ?>
                <?php
                //if (Entrust::can('print_ticket'))
                echo '<a href="lucky/show-report">
                    <div class="col-md-3 col-sm-3 icon">
                        <span class="glyphicon glyphicon-list-alt"></span>
                        <h2>Report</h2>
                    </div>
                </a>
                ';
                ?>
            </div>
        <?php } ?>
</div>
@stop