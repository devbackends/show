import Container from "./bootstrap-blocks/container";
import Column from "./bootstrap-blocks/column";
import Row from "./bootstrap-blocks/row";
import { MyBlock } from './custom-blocks/my-block';

Laraberg.registerCategory('Bootstrap Blocks', 'wp-bootstrap-blocks');
Laraberg.registerBlock('wp-bootstrap-blocks/container', Container);
Laraberg.registerBlock('wp-bootstrap-blocks/column', Column);
Laraberg.registerBlock('wp-bootstrap-blocks/row', Row);

Laraberg.registerCategory('Devvly Blocks', 'devvly-blocks');
Laraberg.registerBlock('devvly-blocks/my-block', MyBlock);