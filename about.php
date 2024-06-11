<ul>
    <li>name</li>
    <li>plcae</li>
    <li>age</li>
    <li></li>
    <li></li>
</ul>
<form action="index.php" method="post">
    <label for="name">name</label>
    <input type="text" placeholder="name">
</form>

<table border>
    <th>
    <td>name</td>
    <td>age</td>
    <td>place</td>
    </th>
    <tr>
        <td>Akshaj</td>
        <td>25</td>
        <td>elathur</td>
    </tr>
    <tr>
        <td>Shinoop</td>
        <td>25</td>
        <td>elathur</td>
    </tr>
</table>
<div class="main-div">
    <div class="square">1</div>
    <div class="square">2</div>
    <div class="square">3</div>
    <div class="square">4</div>
</div>

<style>
    .square {
        width: 100px;
        height: 100px;
        border: 1px solid black;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .main-div{
        display: flex;
        width: 100%;
        justify-content: space-evenly;
        flex-direction: column;
    }
</style>