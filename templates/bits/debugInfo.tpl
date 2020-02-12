{if Config::LOG_SQL_QUERIES}
  <div class="card">
    <div class="card-header">Idiorm queries</div>
    <div class="card-body small text-muted">
        {foreach ORM::get_query_log() as $query}
          {$query}<br>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}
