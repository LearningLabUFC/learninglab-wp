<?php
$show_socials = $args['show_socials'] ?? true;
$setor_html   = $args['setor_html'] ?? '';
?>

<div class="membro-item <?php echo is_membro_formado(get_the_ID()) ? 'formado' : ''; ?>">
    <i class="fa-solid fa-graduation-cap icon-formado"></i>

    <?php if (has_post_thumbnail()) : ?>
        <div class="membro-avatar"><?php the_post_thumbnail('thumbnail'); ?></div>
    <?php endif; ?>

    <h4 class="membro-nome"><?php learninglab_membro_nome_duas_linhas(); ?></h4>

    <?php if ($show_socials) learninglab_render_membro_socials(get_the_ID()); ?>

    <?php if ($setor_html) echo wp_kses_post($setor_html); ?>
</div>