<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Módulo de Estoque — Aula 6</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f5f8;margin:0;color:#222}
header{background:#35205f;color:white;padding:18px 5%;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
main{max-width:1100px;margin:28px auto;padding:0 16px}
.card{background:white;border-radius:10px;padding:20px;margin-bottom:18px;box-shadow:0 2px 10px #0000000b}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px}
input,select,textarea,button{font:inherit;padding:10px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
input,select,textarea{width:100%;margin:5px 0 12px}
button,.btn{background:#56368b;color:white;border:0;padding:10px 14px;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:#5c6470}.danger{background:#a62943}
table{width:100%;border-collapse:collapse;background:white}th,td{text-align:left;padding:10px;border-bottom:1px solid #eee}th{background:#f7f5fb}
.status{font-weight:bold}.DISPONIVEL{color:#187443}.INDISPONIVEL{color:#a65d00}.DESCONTINUADO{color:#a62943}
.msg{padding:12px;background:#e9f7ee;border-radius:6px;margin-bottom:16px}.erro{background:#ffeded}
small{color:#666}.inline{display:inline}
</style>
</head>
<body>
<header><strong>Módulo de Estoque</strong>
<?php if (!empty($_SESSION['usuario'])): ?>
<span>Usuário: <?= htmlspecialchars($_SESSION['usuario']['usuario']) ?> (<?= htmlspecialchars($_SESSION['usuario']['perfil']) ?>)
<a class="btn btn-secondary" href="?action=logout">Sair</a></span>
<?php endif; ?>
</header><main>
