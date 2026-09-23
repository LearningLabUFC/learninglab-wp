/**
 * Slider Gallery — Bloco Gutenberg
 *
 * Editor (React/JSX) — 100% em português com setas nas laterais e pontos abaixo.
 */

import { registerBlockType } from '@wordpress/blocks';
import {
	useBlockProps,
	MediaPlaceholder,
	MediaUpload,
	MediaUploadCheck,
	InspectorControls,
	BlockControls,
} from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	SelectControl,
	ToggleControl,
	ToolbarGroup,
	ToolbarButton,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

import './editor.css';
import metadata from '../includes/blocks/slider-gallery/block.json';

// Ícone do carrossel
const sliderGalleryIcon = (
	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect x="2" y="6" width="3" height="12" rx="1.5" fill="currentColor" opacity="0.4" />
		<rect x="7" y="4" width="10" height="16" rx="2" stroke="currentColor" strokeWidth="1.8" fill="none" />
		<rect x="19" y="6" width="3" height="12" rx="1.5" fill="currentColor" opacity="0.4" />
	</svg>
);

function Edit({ attributes, setAttributes }) {
	const {
		images,
		imageSize,
		linkTo,
		displayNav,
		displayBullets,
		displayCaptions,
		autoplay,
		loop,
	} = attributes;

	const blockProps = useBlockProps({ className: 'll-slider-gallery-editor-wrap' });
	const [activeSlide, setActiveSlide] = useState(0);

	// Normaliza as imagens vindas do seletor de mídia
	const handleSelectImages = (media) => {
		const mediaArray = Array.isArray(media) ? media : [media];
		const formatted = mediaArray.map((img) => ({
			id: img.id,
			url: img.sizes?.[imageSize]?.url || img.url || img.source_url || '',
			fullUrl: img.sizes?.full?.url || img.url || '',
			alt: img.alt || img.alt_text || '',
			caption: img.caption?.raw || img.caption || '',
			width: img.width || 0,
			height: img.height || 0,
		}));
		setAttributes({ images: formatted });
		if (activeSlide >= formatted.length) {
			setActiveSlide(0);
		}
	};

	const imageIds = images.map((img) => img.id);

	// Navegação no preview do editor
	const handlePrev = (e) => {
		e.stopPropagation();
		setActiveSlide((prev) => (prev > 0 ? prev - 1 : images.length - 1));
	};

	const handleNext = (e) => {
		e.stopPropagation();
		setActiveSlide((prev) => (prev < images.length - 1 ? prev + 1 : 0));
	};

	// ─── Toolbar do Bloco ──────────────────────────────────────────────────
	const toolbarControls = images.length > 0 && (
		<BlockControls>
			<ToolbarGroup>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={handleSelectImages}
						allowedTypes={['image']}
						multiple
						gallery
						value={imageIds}
						render={({ open }) => (
							<ToolbarButton
								icon="edit"
								label={__('Editar e reordenar galeria', 'learninglab-core')}
								onClick={open}
							/>
						)}
					/>
				</MediaUploadCheck>
			</ToolbarGroup>
		</BlockControls>
	);

	// ─── Painel Lateral (Configurações em Português) ───────────────────────
	const inspector = (
		<InspectorControls>
			<PanelBody title={__('Configurações do Carrossel', 'learninglab-core')} initialOpen={true}>
				<div className="ll-sg-inspector-label">{__('IMAGENS', 'learninglab-core')}</div>
				<div className="ll-sg-inspector-media-box">
					<div className="ll-sg-inspector-media-box-title">
						<span className="dashicons dashicons-format-gallery"></span>
						<span>{__('Imagens', 'learninglab-core')}</span>
					</div>

					<MediaUploadCheck>
						<MediaUpload
							onSelect={handleSelectImages}
							allowedTypes={['image']}
							multiple
							gallery
							value={imageIds}
							render={({ open }) => (
								<Button
									variant="primary"
									className="ll-sg-sidebar-btn-primary"
									onClick={open}
								>
									{__('Enviar', 'learninglab-core')}
								</Button>
							)}
						/>
					</MediaUploadCheck>

					<MediaUploadCheck>
						<MediaUpload
							onSelect={handleSelectImages}
							allowedTypes={['image']}
							multiple
							gallery
							value={imageIds}
							render={({ open }) => (
								<Button
									variant="secondary"
									className="ll-sg-sidebar-btn-secondary"
									onClick={open}
								>
									{__('Biblioteca de Mídia', 'learninglab-core')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
				</div>

				<SelectControl
					label={__('TAMANHO DAS IMAGENS', 'learninglab-core')}
					value={imageSize}
					options={[
						{ label: __('Miniatura', 'learninglab-core'), value: 'thumbnail' },
						{ label: __('Médio', 'learninglab-core'), value: 'medium' },
						{ label: __('Grande', 'learninglab-core'), value: 'large' },
						{ label: __('Tamanho Original', 'learninglab-core'), value: 'full' },
					]}
					onChange={(val) => setAttributes({ imageSize: val })}
				/>

				<SelectControl
					label={__('VINCULAR A', 'learninglab-core')}
					value={linkTo}
					options={[
						{ label: __('Nenhum', 'learninglab-core'), value: 'none' },
						{ label: __('Arquivo de Mídia', 'learninglab-core'), value: 'file' },
						{ label: __('Página do Anexo', 'learninglab-core'), value: 'attachment' },
					]}
					onChange={(val) => setAttributes({ linkTo: val })}
				/>

				<ToggleControl
					label={__('Exibir botões Anterior e Próximo', 'learninglab-core')}
					checked={displayNav}
					onChange={(val) => setAttributes({ displayNav: val })}
				/>

				<ToggleControl
					label={__('Exibir pontos indicadores', 'learninglab-core')}
					checked={displayBullets}
					onChange={(val) => setAttributes({ displayBullets: val })}
				/>

				<ToggleControl
					label={__('Exibir legendas das fotos', 'learninglab-core')}
					checked={displayCaptions}
					onChange={(val) => setAttributes({ displayCaptions: val })}
				/>

				<ToggleControl
					label={__('Reprodução automática (Autoplay)', 'learninglab-core')}
					checked={autoplay}
					onChange={(val) => setAttributes({ autoplay: val })}
				/>

				<ToggleControl
					label={__('Repetição contínua (Loop)', 'learninglab-core')}
					checked={loop}
					onChange={(val) => setAttributes({ loop: val })}
				/>
			</PanelBody>
		</InspectorControls>
	);

	// ─── Estado Vazio: MediaPlaceholder padrão em Português ────────────────
	if (images.length === 0) {
		return (
			<div {...blockProps}>
				{toolbarControls}
				{inspector}
				<MediaPlaceholder
					icon={<span className="dashicons dashicons-format-gallery" />}
					labels={{
						title: __('Galeria de Fotos', 'learninglab-core'),
						instructions: __(
							'Arraste imagens, envie novas fotos ou selecione arquivos da sua biblioteca de mídia.',
							'learninglab-core'
						),
					}}
					onSelect={handleSelectImages}
					accept="image/*"
					allowedTypes={['image']}
					multiple
					gallery
				/>
			</div>
		);
	}

	// ─── Estado com Imagens: Preview com Setas nas Laterais e Pontos Abaixo ─
	const currentImage = images[activeSlide] || images[0];

	return (
		<div {...blockProps}>
			{toolbarControls}
			{inspector}

			<div className="ll-sg-editor-preview">
				{/* Linha horizontal: Seta Esquerda + Imagem Central + Seta Direita */}
				<div className="ll-sg-editor-carousel-row">
					{displayNav && images.length > 1 && (
						<button
							type="button"
							className="ll-sg-editor-arrow ll-sg-editor-arrow--prev"
							onClick={handlePrev}
							title={__('Slide anterior', 'learninglab-core')}
						>
							‹
						</button>
					)}

					<div className="ll-sg-editor-slide">
						<img
							src={currentImage.url || currentImage.fullUrl}
							alt={currentImage.alt || ''}
							className="ll-sg-editor-img"
						/>

						{displayCaptions && currentImage.caption && (
							<div className="ll-sg-editor-caption">{currentImage.caption}</div>
						)}

						{/* Botão de editar galeria sobreposto no slide */}
						<MediaUploadCheck>
							<MediaUpload
								onSelect={handleSelectImages}
								allowedTypes={['image']}
								multiple
								gallery
								value={imageIds}
								render={({ open }) => (
									<button
										type="button"
										className="ll-sg-editor-edit-btn"
										onClick={open}
										title={__('Editar e reordenar fotos', 'learninglab-core')}
									>
										<span className="dashicons dashicons-edit"></span>
										<span>{__('Editar galeria', 'learninglab-core')} ({images.length})</span>
									</button>
								)}
							/>
						</MediaUploadCheck>
					</div>

					{displayNav && images.length > 1 && (
						<button
							type="button"
							className="ll-sg-editor-arrow ll-sg-editor-arrow--next"
							onClick={handleNext}
							title={__('Próximo slide', 'learninglab-core')}
						>
							›
						</button>
					)}
				</div>

				{/* Pontos indicadores posicionados FORA e LOGO ABAIXO da imagem */}
				{displayBullets && images.length > 1 && (
					<div className="ll-sg-editor-bullets" role="tablist" aria-label={__('Pontos indicadores', 'learninglab-core')}>
						{images.map((_, idx) => (
							<button
								key={idx}
								type="button"
								className={`ll-sg-editor-bullet ${
									idx === activeSlide ? 'is-active' : ''
								}`}
								onClick={(e) => {
									e.stopPropagation();
									setActiveSlide(idx);
								}}
								aria-label={`Slide ${idx + 1}`}
							/>
						))}
					</div>
				)}

				{/* Faixa de miniaturas abaixo dos pontos */}
				{images.length > 1 && (
					<div className="ll-sg-editor-thumb-strip">
						{images.map((img, idx) => (
							<div
								key={img.id || idx}
								className={`ll-sg-editor-thumb-item ${
									idx === activeSlide ? 'is-active' : ''
								}`}
								onClick={() => setActiveSlide(idx)}
								title={`Ver imagem ${idx + 1}`}
							>
								<img src={img.url || img.fullUrl} alt="" />
								<span className="ll-sg-editor-thumb-num">{idx + 1}</span>
							</div>
						))}
					</div>
				)}
			</div>
		</div>
	);
}

// ─── Registro do Bloco ────────────────────────────────────────────────────────
registerBlockType(metadata.name, {
	icon: sliderGalleryIcon,
	edit: Edit,
	save: () => null,
});
