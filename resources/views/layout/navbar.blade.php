<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li {{ setActive('/') }}><a href="/"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
                <li {{ setActive('book') }}><a href="/book"><i class="icon-book"></i><span>Books</span> </a> </li>
                <li {{ setActive('transaction') }}><a href="/transaction"><i class="icon-money"></i><span>Transaction</span> </a></li>
            </ul>
        </div>
    </div>
</div>