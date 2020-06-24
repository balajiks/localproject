<?php echo app('arrilot.widget')->run('Tickets.TotalsWidget'); ?>
<?php echo app('arrilot.widget')->run('Tickets.TotalsWidget2'); ?>
<div class="row panel-body">
    <?php echo app('arrilot.widget')->run('Tickets.RepliesChartWidget'); ?>
    <?php echo app('arrilot.widget')->run('Tickets.YearlyChartWidget'); ?>
</div>