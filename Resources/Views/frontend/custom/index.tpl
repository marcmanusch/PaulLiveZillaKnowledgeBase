{extends file="parent:frontend/custom/index.tpl"}

{block name="frontend_custom_article_content"}

    {if $sPage == 1 || !$paulActiveKnowledge}

        {$sContent}

    {/if}


    {if $sCustomPage.id == $paulPageID}

        {function name=printFAQ}
            {foreach $items as $item}
                <div class="collapse--header">
                    {$item['Title']}
                    <span class="collapse--toggler"></span>
                </div>
                <div class="collapse--content">
                    {if $item['children']}
                        {call name=printFAQ items=$item['children']}

                    {else}
                        <p>{$item.Value}</p>
                    {/if}

                </div>
            {/foreach}

        {/function}

        {call name=printFAQ items=$paulKnowledge}

    {/if}


{/block}