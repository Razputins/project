<div class="container">
	<div class="num">
		<div>
			<span>Всего</span>
			<?= $requests['over'] ?>
		</div>
		<div>
			<span>Где new = 1</span>
			<?= $requests['new'] ?>
		</div>
	</div>
</div>
<style>
    .container{
        display: flex;
        flex-flow: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }
    .num{
        display: flex;
        flex-flow: row;
        justify-content: center;
        border-radius: 5px;
        border: 1px solid #000;
    }
    .num span{
        font-size: 18px;
        font-weight: 600;
    }
    .num > div:first-child{
        border-right: 1px solid #000000;
    }
    .num > div{
        display: flex;
        width: 200px;
        flex-flow: column;
        align-items: center;
        font-size: 24px;
    }
</style>