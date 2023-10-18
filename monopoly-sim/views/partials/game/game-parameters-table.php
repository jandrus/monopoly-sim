<?php


	$free_capital = money(0);
	$debt = money(0);
	$income = money(0);
	$expenses = money(0);
	$profit = money(0);
	$month = 0;

    use Game\Simulation;

    if(isset($_SESSION['game'])) {
		$free_capital = money($_SESSION['game']->free_capital);
		$debt = money($_SESSION['game']->debt);
		$income = $_SESSION['game']->getIncome();
		$expenses = $_SESSION['game']->getExpenses();
		$profit = money($income - $expenses);
		$income = money($income);
		$expenses = money($expenses);
		$game_date = $_SESSION['game']->getDate();
    }
?>
<div class="container">
	<table class="text-center w-full">
		<thead class="bg-red flex w-full">
			<tr class="flex w-full mb-0">
				<th class="p-1 w-1/6 text-gray-800">Free Capital <?= $free_capital ?></th>
				<th class="p-1 w-1/6 text-gray-800">Debt <?= $debt ?></th>
				<th class="p-1 w-1/6 text-gray-800">Income <?= $income ?></th>
				<th class="p-1 w-1/6 text-gray-800">Expenses <?= $expenses ?></th>
				<th class="p-1 w-1/6 text-gray-800">Profit <?= $profit ?></th>
				<th class="p-1 w-1/6 text-gray-800">Elapsed Time: <?= $game_date ?></th>
			</tr>
		</thead>
	</table>
</div>
