import { Type } from 'main.core';
import { React } from 'ui.react-base'; // Штатный React из ядра
import { createRoot } from 'react';

const container = document.getElementById('faleev-faleev.tender-root');
if (Type.isDomNode(container))
{
	const root = createRoot(container);
	root.render(<h1>React внутри Tender успешно запущен!</h1>);
}
