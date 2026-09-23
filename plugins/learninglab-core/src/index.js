/**
 * Slider Gallery — Bloco Gutenberg
 *
 * Editor (React/JSX) — interface fiel à do MacMagazine e com Swiper preview.
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

// Ícone idêntico ao do bloco no Gutenberg do MacMagazine (slider com 2 barras laterais)
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

	// Normaliza as imagens vindas do MediaUpload / MediaPlaceholder
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
								label={__('Editar galeria', 'learninglab-core')}
								onClick={open}
							/>
						)}
					/>
				</MediaUploadCheck>
			</ToolbarGroup>
		</BlockControls>
	);

	// ─── Sidebar / InspectorControls (idêntico ao MacMagazine) ─────────────
	const inspector = (
		<InspectorControls>
			<PanelBody title={__('Block Settings', 'learninglab-core')} initialOpen={true}>
				<div className="ll-sg-inspector-label">{__('IMAGES', 'learninglab-core')}</div>
				<div className="ll-sg-inspector-media-box">
					<div className="ll-sg-inspector-media-box-title">
						<span className="dashicons dashicons-format-gallery"></span>
						<span>{__('Images', 'learninglab-core')}</span>
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
									{__('Biblioteca de mídia', 'learninglab-core')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
				</div>

				<SelectControl
					label={__('IMAGES SIZE', 'learninglab-core')}
					value={imageSize}
					options={[
						{ label: 'thumbnail', value: 'thumbnail' },
						{ label: 'medium', value: 'medium' },
						{ label: 'large', value: 'large' },
						{ label: 'full', value: 'full' },
					]}
					onChange={(val) => setAttributes({ imageSize: val })}
				/>

				<SelectControl
					label={__('LINK TO', 'learninglab-core')}
					value={linkTo}
					options={[
						{ label: __('None', 'learninglab-core'), value: 'none' },
						{ label: __('Media File', 'learninglab-core'), value: 'file' },
						{ label: __('Attachment Page', 'learninglab-core'), value: 'attachment' },
					]}
					onChange={(val) => setAttributes({ linkTo: val })}
				/>

				<ToggleControl
					label={__('Display Previous & Next Buttons', 'learninglab-core')}
					checked={displayNav}
					onChange={(val) => setAttributes({ displayNav: val })}
				/>

				<ToggleControl
					label={__('Display Bullets', 'learninglab-core')}
					checked={displayBullets}
					onChange={(val) => setAttributes({ displayBullets: val })}
				/>

				<ToggleControl
					label={__('Display Captions', 'learninglab-core')}
					checked={displayCaptions}
					onChange={(val) => setAttributes({ displayCaptions: val })}
				/>

				<ToggleControl
					label={__('Autoplay', 'learninglab-core')}
					checked={autoplay}
					onChange={(val) => setAttributes({ autoplay: val })}
				/>

				<ToggleControl
					label={__('Loop infinito', 'learninglab-core')}
					checked={loop}
					onChange={(val) => setAttributes({ loop: val })}
				/>
			</PanelBody>
		</InspectorControls>
	);

	// ─── Estado Vazio: MediaPlaceholder padrão do Gutenberg ────────────────
	if (images.length === 0) {
		return (
			<div {...blockProps}>
				{toolbarControls}
				{inspector}
				<MediaPlaceholder
					icon={<span className="dashicons dashicons-format-gallery" />}
					labels={{
						title: __('Galeria', 'learninglab-core'),
						instructions: __(
							'Drag images, upload new ones or select files from your library.',
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

	// ─── Estado com Imagens: Preview do Slider com estilo do tema ─────────
	const currentImage = images[activeSlide] || images[0];

	return (
		<div {...blockProps}>
			{toolbarControls}
			{inspector}

			<div className="ll-sg-editor-preview">
				<div className="ll-sg-editor-slide">
					<img
						src={currentImage.url || currentImage.fullUrl}
						alt={currentImage.alt || ''}
						className="ll-sg-editor-img"
					/>

					{displayCaptions && currentImage.caption && (
						<div className="ll-sg-editor-caption">{currentImage.caption}</div>
					)}

					{/* Setas com o estilo idêntico ao Swiper da home */}
					{displayNav && images.length > 1 && (
						<>
							<button
								type="button"
								className="ll-sg-editor-arrow ll-sg-editor-arrow--prev"
								onClick={handlePrev}
								aria-label={__('Slide anterior', 'learninglab-core')}
							>
								‹
							</button>
							<button
								type="button"
								className="ll-sg-editor-arrow ll-sg-editor-arrow--next"
								onClick={handleNext}
								aria-label={__('Próximo slide', 'learninglab-core')}
							>
								›
							</button>
						</>
					)}

					{/* Botão de editar galeria sobreposto */}
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
									title={__('Editar e reordenar imagens', 'learninglab-core')}
								>
									<span className="dashicons dashicons-edit"></span>
									<span>{__('Editar galeria', 'learninglab-core')} ({images.length})</span>
								</button>
							)}
						/>
					</MediaUploadCheck>
				</div>

				{/* Bullets de paginação no preview */}
				{displayBullets && images.length > 1 && (
					<div className="ll-sg-editor-bullets">
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
							/>
						))}
					</div>
				)}

				{/* Faixa de miniaturas abaixo para fácil alternância */}
				{images.length > 1 && (
					<div className="ll-sg-editor-thumb-strip">
						{images.map((img, idx) => (
							<div
								key={img.id || idx}
								className={`ll-sg-editor-thumb-item ${
									idx === activeSlide ? 'is-active' : ''
								}`}
								onClick={() => setActiveSlide(idx)}
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
	save: () => null, // Renderização server-side via render.php
});
