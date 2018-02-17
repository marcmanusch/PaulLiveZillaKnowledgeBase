{extends file="parent:frontend/custom/index.tpl"}

{block name="frontend_custom_article_content"}

    {if $sPage == 1 || !$paulShowKnowledge}

        {$sContent}

    {/if}

    {if $paulKnowledge}
        <div class="paul-knowledge--content">
            {block name='frontend_custom_paul_knowledge'}
                {include file="frontend/custom/knowledge/paulKnowledge.tpl"}
            {/block}
        </div>
    {/if}

{/block}