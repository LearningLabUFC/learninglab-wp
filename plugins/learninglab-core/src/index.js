/**
 * Slider Gallery — Bloco Gutenberg
 * Editor (React/JSX) — compilado via @wordpress/scripts
 */

import { registerBlockType } from '@wordpress/blocks';
import {
	useBlockProps,
	MediaUpload,
	MediaUploadCheck,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	ToggleControl,
	RangeControl,
	Placeholder,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './editor.css';
import metadata from '../includes/blocks/slider-gallery/block.json';

// ─── Miniatura individual ─────────────────────────────────────────────────────
function ImageThumb({ image, index, total, onRemove, onMoveLeft, onMoveRight }) {
	return (
		<div className="ll-sg-thumb">
			<img src={ image.url } alt={ image.alt || '' } />
			<div className="ll-sg-thumb-overlay">
				{ index > 0 && (
					<button
						className="ll-sg-action ll-sg-action--left"
						onClick={ () => onMoveLeft( index ) }
						title={ __( 'Mover para esquerda', 'learninglab-core' ) }
					>‹</button>
				)}
				<button
					className="ll-sg-action ll-sg-action--remove"
					onClick={ () => onRemove( index ) }
					title={ __( 'Remover', 'learninglab-core' ) }
				>×</button>
				{ index < total - 1 && (
					<button
						className="ll-sg-action ll-sg-action--right"
						onClick={ () => onMoveRight( index ) }
						title={ __( 'Mover para direita', 'learninglab-core' ) }
					>›</button>
				)}
			</div>
			<div className="ll-sg-thumb-index">{ index + 1 }</div>
		</div>
	);
}

// ─── Edit ─────────────────────────────────────────────────────────────────────
function Edit({ attributes, setAttributes }) {
	const { images, autoplay, loop, showCaptions, autoplayDelay } = attributes;
	const blockProps = useBlockProps({ className: 'll-slider-gallery-editor' });

	// Normaliza objetos vindos do MediaUpload
	const normalizeImages = ( media ) =>
		( Array.isArray( media ) ? media : [ media ] ).map( ( img ) => ({
			id:      img.id,
			url:     img.url || img.source_url || '',
			alt:     img.alt || img.alt_text || '',
			caption: img.caption?.raw || img.caption || '',
			width:   img.width  || 0,
			height:  img.height || 0,
		}) );

	// Adiciona novas imagens sem duplicar
	const handleSelect = ( media ) => {
		const normalized  = normalizeImages( media );
		const existingIds = new Set( images.map( ( i ) => i.id ) );
		const fresh       = normalized.filter( ( i ) => ! existingIds.has( i.id ) );
		setAttributes({ images: [ ...images, ...fresh ] });
	};

	const handleRemove    = ( idx ) => setAttributes({ images: images.filter( ( _, i ) => i !== idx ) });
	const handleMoveLeft  = ( idx ) => {
		const next = [ ...images ];
		[ next[ idx - 1 ], next[ idx ] ] = [ next[ idx ], next[ idx - 1 ] ];
		setAttributes({ images: next });
	};
	const handleMoveRight = ( idx ) => {
		const next = [ ...images ];
		[ next[ idx ], next[ idx + 1 ] ] = [ next[ idx + 1 ], next[ idx ] ];
		setAttributes({ images: next });
	};

	const selectedIds = images.map( ( img ) => img.id );

	// Painel lateral
	const inspector = (
		<InspectorControls>
			<PanelBody title={ __( 'Configurações do Slider', 'learninglab-core' ) } initialOpen>
				<ToggleControl
					label={ __( 'Autoplay', 'learninglab-core' ) }
					checked={ autoplay }
					onChange={ ( v ) => setAttributes({ autoplay: v }) }
				/>
				{ autoplay && (
					<RangeControl
						label={ __( 'Intervalo (ms)', 'learninglab-core' ) }
						value={ autoplayDelay }
						onChange={ ( v ) => setAttributes({ autoplayDelay: v }) }
						min={ 1000 } max={ 10000 } step={ 500 }
					/>
				)}
				<ToggleControl
					label={ __( 'Loop infinito', 'learninglab-core' ) }
					checked={ loop }
					onChange={ ( v ) => setAttributes({ loop: v }) }
				/>
				<ToggleControl
					label={ __( 'Mostrar legendas', 'learninglab-core' ) }
					checked={ showCaptions }
					onChange={ ( v ) => setAttributes({ showCaptions: v }) }
				/>
			</PanelBody>
			{ images.length > 0 && (
				<PanelBody title={ `${ __( 'Imagens', 'learninglab-core' ) } (${ images.length })` } initialOpen={ false }>
					<p className="ll-sg-inspector-hint">
						{ __( 'Use ‹ › nas miniaturas para reordenar. Clique × para remover.', 'learninglab-core' ) }
					</p>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ handleSelect }
							allowedTypes={ [ 'image' ] }
							multiple gallery
							value={ selectedIds }
							render={ ({ open }) => (
								<Button variant="secondary" onClick={ open } style={{ width: '100%', justifyContent: 'center' }}>
									{ __( 'Gerenciar imagens', 'learninglab-core' ) }
								</Button>
							)}
						/>
					</MediaUploadCheck>
				</PanelBody>
			)}
		</InspectorControls>
	);

	// ── Placeholder (sem imagens) ────────────────────────────────────────────
	if ( images.length === 0 ) {
		return (
			<div { ...blockProps }>
				{ inspector }
				<Placeholder
					icon="format-gallery"
					label={ __( 'Slider Gallery', 'learninglab-core' ) }
					instructions={ __( 'Faça upload de imagens ou selecione da sua biblioteca de mídia para criar um carrossel.', 'learninglab-core' ) }
					className="ll-sg-placeholder"
				>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ handleSelect }
							allowedTypes={ [ 'image' ] }
							multiple
							value={ [] }
							render={ ({ open }) => (
								<Button variant="primary" onClick={ open } size="large">
									{ __( '⬆ Fazer Upload de Imagens', 'learninglab-core' ) }
								</Button>
							)}
						/>
					</MediaUploadCheck>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ handleSelect }
							allowedTypes={ [ 'image' ] }
							multiple gallery
							value={ [] }
							render={ ({ open }) => (
								<Button variant="secondary" onClick={ open } size="large">
									{ __( '🖼 Biblioteca de Mídia', 'learninglab-core' ) }
								</Button>
							)}
						/>
					</MediaUploadCheck>
				</Placeholder>
			</div>
		);
	}

	// ── Grade de miniaturas ──────────────────────────────────────────────────
	return (
		<div { ...blockProps }>
			{ inspector }
			<div className="ll-sg-header">
				<div className="ll-sg-header-title">
					<strong>Slider Gallery</strong>
					<span className="ll-sg-count">{ images.length } { images.length === 1 ? 'imagem' : 'imagens' }</span>
				</div>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ handleSelect }
						allowedTypes={ [ 'image' ] }
						multiple gallery
						value={ selectedIds }
						render={ ({ open }) => (
							<Button variant="secondary" onClick={ open } size="small">
								{ __( '+ Adicionar imagens', 'learninglab-core' ) }
							</Button>
						)}
					/>
				</MediaUploadCheck>
			</div>

			<div className="ll-sg-grid">
				{ images.map( ( image, index ) => (
					<ImageThumb
						key={ image.id }
						image={ image }
						index={ index }
						total={ images.length }
						onRemove={ handleRemove }
						onMoveLeft={ handleMoveLeft }
						onMoveRight={ handleMoveRight }
					/>
				))}
			</div>

			<div className="ll-sg-footer">
				<span className="ll-sg-hint">
					💡 { __( 'Use ‹ › para reordenar. O carrossel aparece ao publicar o post.', 'learninglab-core' ) }
				</span>
			</div>
		</div>
	);
}

// ─── Registro ─────────────────────────────────────────────────────────────────
registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null, // renderização via render.php (server-side)
});
