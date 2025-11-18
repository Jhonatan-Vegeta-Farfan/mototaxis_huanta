<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Estadísticas de Requests</h2>
    <a href="index.php?controller=count_request&action=index" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <input type="hidden" name="controller" value="count_request">
            <input type="hidden" name="action" value="estadisticas">
            
            <div class="row">
                <div class="col-md-5">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                           value="<?php echo $fecha_inicio; ?>">
                </div>
                <div class="col-md-5">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                           value="<?php echo $fecha_fin; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-4">
    <!-- Total Requests -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                <h3><?php echo number_format($stats['total_requests']); ?></h3>
                <h5>Total Requests</h5>
            </div>
        </div>
    </div>

    <!-- Requests Hoy -->
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-bolt fa-3x mb-3"></i>
                <h3><?php echo number_format($stats['requests_hoy']); ?></h3>
                <h5>Requests Hoy</h5>
            </div>
        </div>
    </div>

    <!-- Requests Este Mes -->
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-3x mb-3"></i>
                <h3><?php echo number_format($stats['requests_este_mes']); ?></h3>
                <h5>Requests Este Mes</h5>
            </div>
        </div>
    </div>

    <!-- Promedio Diario -->
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x mb-3"></i>
                <h3><?php echo number_format($stats['total_requests'] / max(1, count($stats['por_dia'])), 1); ?></h3>
                <h5>Promedio Diario</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Por Tipo -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Requests por Tipo</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['por_tipo'] as $tipo): ?>
                            <tr>
                                <td><?php echo ucfirst($tipo['tipo']); ?></td>
                                <td><?php echo $tipo['total']; ?></td>
                                <td>
                                    <?php 
                                    $porcentaje = $stats['total_requests'] > 0 ? 
                                        ($tipo['total'] / $stats['total_requests']) * 100 : 0;
                                    echo number_format($porcentaje, 1) . '%';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Por Cliente -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Top Clientes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Requests</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['por_cliente'] as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['razon_social']; ?></td>
                                <td><?php echo $cliente['total']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Requests por Día -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Requests por Día (Últimos 30 días)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Requests</th>
                        <th>Barra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['por_dia'] as $dia): ?>
                    <tr>
                        <td><?php echo $dia['dia']; ?></td>
                        <td><?php echo $dia['total']; ?></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <?php 
                                $maxRequests = max(array_column($stats['por_dia'], 'total'));
                                $width = $maxRequests > 0 ? ($dia['total'] / $maxRequests) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?php echo $width; ?>%" 
                                     aria-valuenow="<?php echo $dia['total']; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="<?php echo $maxRequests; ?>">
                                    <?php echo $dia['total']; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>