<?php
// If someone visits /live_demo/ directly, send them to the PHP live demos showcase
header("Location: ../live-demos", true, 302);
exit;
