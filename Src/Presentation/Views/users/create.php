<!-- Selector de Empleados -->
<div class="column is-6" id="employeeField" style="display: none;">
        <div class="field">
                <label class="label">
                        <i class="fas fa-user-tie"></i> Empleado <span class="has-text-danger">*</span>
                </label>
                <div class="control">
                        <div class="select is-fullwidth">
                                <select name="employee_id">
                                        <option value="">Seleccione un empleado...</option>
                                        <?php if (!empty($employees) && is_array($employees)): ?>
                                                <?php foreach ($employees as $emp): ?>
                                                <?php if (is_object($emp)): ?>
                                                        <option value="<?php echo $emp->getId(); ?>">
                                                        <?php echo htmlspecialchars($emp->getFullName()); ?> - <?php echo $emp->getDni(); ?>
                                                        </option>
                                                <?php else: ?>
                                                        <option value="<?php echo $emp['id']; ?>">
                                                        <?php echo htmlspecialchars($emp['names'] . ' ' . $emp['surnames']); ?> - <?php echo $emp['dni']; ?>
                                                        </option>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                        <?php endif; ?>
                                </select>
                        </div>
                </div>
        </div>
</div>

<!-- Selector de Clientes -->
<div class="column is-6" id="customerField" style="display: none;">
        <div class="field">
                <label class="label">
                        <i class="fas fa-user-friends"></i> Cliente <span class="has-text-danger">*</span>
                </label>
                <div class="control">
                        <div class="select is-fullwidth">
                                <select name="customer_id">
                                        <option value="">Seleccione un cliente...</option>
                                        <?php if (!empty($customers) && is_array($customers)): ?>
                                                <?php foreach ($customers as $cust): ?>
                                                <?php if (is_object($cust)): ?>
                                                        <option value="<?php echo $cust->getId(); ?>">
                                                        <?php echo htmlspecialchars($cust->getFullName()); ?> - <?php echo $cust->getDni(); ?>
                                                        </option>
                                                <?php else: ?>
                                                        <option value="<?php echo $cust['id']; ?>">
                                                        <?php echo htmlspecialchars($cust['full_name']); ?> - <?php echo $cust['dni']; ?>
                                                        </option>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                        <?php endif; ?>
                                </select>
                        </div>
                </div>
        </div>
</div>