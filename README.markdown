
FieldFrame Expansion Pack
======================================================================

FFM Hierarchy
----------------------------------------------------------------------

FFM Hierarchy allows you to create nested rows within your FF Matrix fields.

### Template usage

In the following examples, `{nav}` is an FF Matrix field, and `{subpages}` is the FFM Hierarchy column name.

FFM Hierarchy's single tag will use the same template of the parent FF Matrix field:

	<ul>
		{nav}
			<li><a href="{page_url}">{page_title}</a>
				{if subpages}
					<ul>{subpages}</ul>
				{/if}
			</li>
		{/nav}
	</ul>

But you can override that by using a tag pair:

	<ul>
		{nav}
			<li><strong><a href="{page_url}">{page_title}</a>{if subpages}:{/if}</strong>
				{subpages backspace="2"}<a href="{page_url}">{page_title}</a>, {/subpages}
			</li>
		{/nav}
	</ul>
